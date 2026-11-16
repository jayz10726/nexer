<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $gatewayAmount = (int) round($amountUsd * config('currency.paystack.usd_rate') * 100);

        try {
            $response = Http::withToken(config('services.paystack.secret_key'))
                ->acceptJson()
                ->post('https://api.paystack.co/transaction/initialize', [
                    'email' => $user->email,
                    'amount' => $gatewayAmount,
                    'currency' => config('currency.paystack.code'),
                    'reference' => $reference,
                    'channels' => [$validated['payment_method']],
                    'callback_url' => route('dashboard.wallet.callback'),
                    'metadata' => [
                        'user_id' => $user->id,
                        'purpose' => 'wallet_topup',
                        'wallet_amount_usd' => $amountUsd,
                        'usd_to_paystack_rate' => config('currency.paystack.usd_rate'),
                    ],
                ]);
        } catch (ConnectionException) {
            return back()->withErrors(['payment' => 'Paystack could not be reached. Please try again.']);
        }

        if (! $response->successful() || ! $response->json('status') || ! $response->json('data.authorization_url')) {
            return back()->withErrors(['payment' => $response->json('message', 'Unable to start payment. Please try again.')]);
        }

        return redirect()->away($response->json('data.authorization_url'));
    }

    public function callback(Request $request): RedirectResponse
    {
        $reference = $request->string('reference')->toString();

        if ($reference === '') {
            return redirect()->route('dashboard.wallet')->withErrors(['payment' => 'No Paystack payment reference was returned.']);
        }

        try {
            $response = Http::withToken(config('services.paystack.secret_key'))
                ->acceptJson()
                ->get('https://api.paystack.co/transaction/verify/' . urlencode($reference));
        } catch (ConnectionException) {
            return redirect()->route('dashboard.wallet')->withErrors(['payment' => 'Payment verification could not be completed. Please contact support if you were charged.']);
        }

        $payment = $response->json('data');
        $user = $request->user();

        if (! $response->successful() || $response->json('data.status') !== 'success' || $payment['customer']['email'] !== $user->email) {
            return redirect()->route('dashboard.wallet')->withErrors(['payment' => 'Paystack could not verify this payment. No funds were added.']);
        }

        if (Transaction::where('description', 'Paystack payment ' . $reference)->exists()) {
            return redirect()->route('dashboard.wallet')->with('status', 'This payment was already added to your wallet.');
        }

        $amount = round(((int) $payment['amount']) / 100 / config('currency.paystack.usd_rate'), 2);
        $user->increment('wallet_balance', $amount);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'topup',
            'amount' => $amount,
            'balance_after' => $user->fresh()->wallet_balance,
            'description' => 'Paystack payment ' . $reference,
            'status' => 'completed',
        ]);

        return redirect()->route('dashboard.wallet')->with('status', 'Payment confirmed. Your wallet has been credited.');
    }
}
