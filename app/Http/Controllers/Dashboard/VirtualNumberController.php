<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PricingCatalog;
use App\Support\ServiceCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VirtualNumberController extends Controller
{
    public function index(Request $request): View
    {
        $numbers = $request->user()->virtualNumbers()->latest()->paginate(10);

        return view('dashboard.numbers.index', [
            'numbers' => $numbers,
            'continents' => config('geo'),
        ]);
    }

    public function browse(Request $request): View
    {
        $continents = config('geo');
        $selectedContinent = $request->query('continent');

        if (! is_string($selectedContinent) || ! array_key_exists($selectedContinent, $continents)) {
            $selectedContinent = '';
        }

        return view('dashboard.numbers.buy', [
            'continents' => $continents,
            'serviceGroups' => ServiceCatalog::groups(),
            'serviceLabels' => array_map(fn ($s) => $s['label'], ServiceCatalog::all()),
            'selectedContinent' => $selectedContinent,
        ]);
    }

    /**
     * Small JSON endpoint the buy page calls after a continent/country/service
     * is picked, so the price shown to the user always matches what the
     * server will actually charge.
     */
    public function price(Request $request): JsonResponse
    {
        $validated = $this->validateSelection($request);

        [$min, $max] = PricingCatalog::range('number');
        $price = PricingCatalog::price("{$validated['continent']}|{$validated['country']}|{$validated['service']}", $min, $max);

        return response()->json(['price' => $price]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSelection($request);

        [$min, $max] = PricingCatalog::range('number');
        $price = PricingCatalog::price("{$validated['continent']}|{$validated['country']}|{$validated['service']}", $min, $max);
        $userId = $request->user()->id;
        $dialCode = config("geo.{$validated['continent']}.{$validated['country']}", '000');

        $purchased = DB::transaction(function () use ($validated, $price, $userId, $dialCode) {
            $user = User::query()->lockForUpdate()->findOrFail($userId);

            if ($user->wallet_balance < $price) {
                return false;
            }

            $user->decrement('wallet_balance', $price);

            $user->virtualNumbers()->create([
                'continent' => $validated['continent'],
                'country' => $validated['country'],
                'service' => $validated['service'],
                'price' => $price,
                'phone_number' => $this->fakeNumber($dialCode),
                'capability' => 'sms',
                'status' => 'active',
                'expires_at' => now()->addDays(30),
            ]);

            $user->transactions()->create([
                'type' => 'purchase',
                'amount' => $price,
                'balance_after' => $user->wallet_balance,
                'description' => ServiceCatalog::label($validated['service'])." number — {$validated['country']}",
                'status' => 'completed',
            ]);

            return true;
        });

        if (! $purchased) {
            return redirect()->route('dashboard.wallet')
                ->with('status', 'Insufficient balance for this number. Add funds to your wallet and try again.');
        }

        return redirect()->route('dashboard.numbers')->with('status', 'Virtual number purchased for $'.$price.". It's ready in Virtual Numbers.");
    }

    private function validateSelection(Request $request): array
    {
        $geo = config('geo');
        $services = array_keys(ServiceCatalog::all());

        $validated = $request->validate([
            'continent' => ['required', 'string'],
            'country' => ['required', 'string'],
            'service' => ['required', 'string', 'in:'.implode(',', $services)],
        ]);

        if (! isset($geo[$validated['continent']]) || ! isset($geo[$validated['continent']][$validated['country']])) {
            abort(422, 'Invalid continent/country selection.');
        }

        return $validated;
    }

    private function fakeNumber(string $dialCode): string
    {
        return '+'.$dialCode.' '.random_int(100, 999).' '.random_int(100, 999).' '.random_int(1000, 9999);
    }
}
