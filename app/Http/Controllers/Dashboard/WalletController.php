<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\WalletTopUp;
use Illuminate\Database\QueryException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $transactions = $user->transactions()->latest()->paginate(15);

        return view('dashboard.wallet.index', [
            'walletBalance' => $user->wallet_balance,
            'transactions' => $transactions,
        ]);
    }

    public function topUp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:5', 'max:10000'],
            'payment_method' => ['required', 'in:card,bank,apple_pay,ussd,qr,mobile_money,bank_transfer'],
        ]);

        if (! config('services.paystack.secret_key')) {
            return back()->withErrors(['payment' => 'Paystack is not configured yet. Add PAYSTACK_SECRET_KEY to your environment.']);
        }

        $user = $request->user();
        $reference = 'WZ-' . Str::upper(Str::random(18));
        $amountUsd = round((float) $validated['amount'], 2);
        $rate = round((float) config('currency.paystack.usd_rate'), 6);
        $currency = strtoupper((string) config('currency.paystack.code'));

        if (! is_finite($rate) || $rate <= 0 || ! preg_match('/\A[A-Z]{3}\z/', $currency)) {
            return back()->withErrors(['payment' => 'Paystack currency settings are invalid. Please contact support.']);
        }

        $gatewayAmount = (int) round($amountUsd * $rate * 100);
        $topUp = WalletTopUp::create([
            'user_id' => $user->id,
            'reference' => $reference,
            'wallet_amount' => number_format($amountUsd, 2, '.', ''),
            'currency' => $currency,
            'gateway_amount' => $gatewayAmount,
            'usd_to_paystack_rate' => $rate,
        ]);

        try {
            $response = Http::withToken(config('services.paystack.secret_key'))
                ->acceptJson()
                ->post('https://api.paystack.co/transaction/initialize', [
                    'email' => $user->email,
                    'amount' => $gatewayAmount,
                    'currency' => $currency,
                    'reference' => $reference,
                    'channels' => [$validated['payment_method']],
                    'callback_url' => route('dashboard.wallet.callback'),
                    'metadata' => [
                        'user_id' => $user->id,
                        'purpose' => 'wallet_topup',
                        'wallet_amount_usd' => $amountUsd,
                        'usd_to_paystack_rate' => $rate,
                    ],
                ]);
        } catch (ConnectionException) {
            return back()->withErrors(['payment' => 'Paystack could not be reached. Please try again.']);
        }

        if (! $response->successful() || ! $response->json('status') || ! $response->json('data.authorization_url')) {
            $topUp->update(['status' => 'failed']);

            return back()->withErrors(['payment' => $response->json('message', 'Unable to start payment. Please try again.')]);
        }

        return redirect()->away($response->json('data.authorization_url'));
    }

    public function callback(Request $request): RedirectResponse
    {
        $reference = $request->string('reference')->toString();
        $user = $request->user();

        if (! preg_match('/\AWZ-[A-Z0-9]{18}\z/', $reference)) {
            return redirect()->route('dashboard.wallet')->withErrors(['payment' => 'No Paystack payment reference was returned.']);
        }

        if (! config('services.paystack.secret_key')) {
            return redirect()->route('dashboard.wallet')->withErrors(['payment' => 'Payment verification is unavailable. Please contact support.']);
        }

        $topUp = WalletTopUp::where('reference', $reference)
            ->where('user_id', $user->id)
            ->first();

        if (! $topUp) {
            return redirect()->route('dashboard.wallet')->withErrors(['payment' => 'Paystack could not verify this payment. No funds were added.']);
        }

        if ($topUp->status === 'credited') {
            return redirect()->route('dashboard.wallet')->with('status', 'This payment was already added to your wallet.');
        }

        if ($topUp->status !== 'pending') {
            return redirect()->route('dashboard.wallet')->withErrors(['payment' => 'This payment cannot be processed. Please contact support.']);
        }

        try {
            $response = Http::withToken(config('services.paystack.secret_key'))
                ->acceptJson()
                ->get('https://api.paystack.co/transaction/verify/' . urlencode($reference));
        } catch (ConnectionException) {
            return redirect()->route('dashboard.wallet')->withErrors(['payment' => 'Payment verification could not be completed. Please contact support if you were charged.']);
        }

        $payment = $response->json('data');
        $metadata = data_get($payment, 'metadata', []);
        $paidAmount = filter_var(data_get($payment, 'amount'), FILTER_VALIDATE_INT);
        $walletAmountCents = (int) round((float) $topUp->wallet_amount * 100);
        $metadataWalletAmountCents = (int) round((float) data_get($metadata, 'wallet_amount_usd', 0) * 100);
        $metadataRate = (float) data_get($metadata, 'usd_to_paystack_rate', 0);

        if (! $response->successful()
            || data_get($payment, 'status') !== 'success'
            || data_get($payment, 'reference') !== $reference
            || ! is_array($metadata)
            || data_get($metadata, 'purpose') !== 'wallet_topup'
            || (string) data_get($metadata, 'user_id') !== (string) $topUp->user_id
            || $metadataWalletAmountCents !== $walletAmountCents
            || abs($metadataRate - (float) $topUp->usd_to_paystack_rate) > 0.000001
            || $paidAmount === false
            || $paidAmount !== (int) $topUp->gateway_amount
            || strtoupper((string) data_get($payment, 'currency')) !== $topUp->currency
            || strtolower(trim((string) data_get($payment, 'customer.email'))) !== strtolower($user->email)) {
            return redirect()->route('dashboard.wallet')->withErrors(['payment' => 'Paystack could not verify this payment. No funds were added.']);
        }

        try {
            $creditResult = DB::transaction(function () use ($topUp, $reference, $user): string {
                $lockedTopUp = WalletTopUp::whereKey($topUp->id)->lockForUpdate()->firstOrFail();

                if ($lockedTopUp->status === 'credited') {
                    return 'already';
                }

                if ($lockedTopUp->status !== 'pending') {
                    return 'unavailable';
                }

                $duplicate = Transaction::where('gateway_reference', $reference)->first();

                if ($duplicate) {
                    if ($duplicate->user_id !== $user->id) {
                        return 'conflict';
                    }

                    $lockedTopUp->update(['status' => 'credited']);

                    return 'already';
                }

                $lockedUser = $user->newQuery()->whereKey($user->id)->lockForUpdate()->firstOrFail();
                $lockedUser->wallet_balance = round(
                    (float) $lockedUser->wallet_balance + (float) $lockedTopUp->wallet_amount,
                    2
                );
                $lockedUser->save();

                Transaction::create([
                    'user_id' => $lockedUser->id,
                    'type' => 'topup',
                    'amount' => $lockedTopUp->wallet_amount,
                    'balance_after' => $lockedUser->wallet_balance,
                    'description' => 'Paystack payment ' . $reference,
                    'status' => 'completed',
                    'gateway_reference' => $reference,
                ]);

                $lockedTopUp->update(['status' => 'credited']);

                return 'credited';
            });
        } catch (QueryException $exception) {
            $duplicate = Transaction::where('gateway_reference', $reference)->first();

            if (! $duplicate) {
                throw $exception;
            }

            if ($duplicate->user_id !== $user->id) {
                return redirect()->route('dashboard.wallet')->withErrors(['payment' => 'Paystack could not verify this payment. No funds were added.']);
            }

            return redirect()->route('dashboard.wallet')->with('status', 'This payment was already added to your wallet.');
        }

        if ($creditResult === 'conflict' || $creditResult === 'unavailable') {
            return redirect()->route('dashboard.wallet')->withErrors(['payment' => 'This payment cannot be processed. Please contact support.']);
        }

        if ($creditResult === 'already') {
            return redirect()->route('dashboard.wallet')->with('status', 'This payment was already added to your wallet.');
        }

        return redirect()->route('dashboard.wallet')->with('status', 'Payment confirmed. Your wallet has been credited.');
    }
}
