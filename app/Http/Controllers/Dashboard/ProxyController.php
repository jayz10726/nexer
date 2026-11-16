<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PricingCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProxyController extends Controller
{
    public function index(Request $request): View
    {
        $proxies = $request->user()->proxies()->latest()->paginate(10);

        return view('dashboard.proxies.index', [
            'proxies' => $proxies,
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

        return view('dashboard.proxies.buy', [
            'continents' => $continents,
            'types' => PricingCatalog::proxyTypes(),
            'selectedContinent' => $selectedContinent,
        ]);
    }

    /**
     * Small JSON endpoint the buy page calls after a continent/country/type
     * is picked, so the price shown to the user always matches what the
     * server will actually charge.
     */
    public function price(Request $request): JsonResponse
    {
        $validated = $this->validateSelection($request);

        [$min, $max] = PricingCatalog::range('proxy');
        $price = PricingCatalog::price("{$validated['continent']}|{$validated['country']}|{$validated['type']}", $min, $max);

        return response()->json(['price' => $price]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSelection($request);

        [$min, $max] = PricingCatalog::range('proxy');
        $price = PricingCatalog::price("{$validated['continent']}|{$validated['country']}|{$validated['type']}", $min, $max);
        $userId = $request->user()->id;

        $purchased = DB::transaction(function () use ($validated, $price, $userId) {
            $user = User::query()->lockForUpdate()->findOrFail($userId);

            if ($user->wallet_balance < $price) {
                return false;
            }

            $user->decrement('wallet_balance', $price);

            $user->proxies()->create([
                'continent' => $validated['continent'],
                'country' => $validated['country'],
                'type' => $validated['type'],
                'price' => $price,
                'ip_address' => $this->fakeIp(),
                'port' => random_int(1024, 65000),
                'username' => 'user'.$user->id.'_'.random_int(100, 999),
                'password' => bin2hex(random_bytes(4)),
                'status' => 'active',
                'expires_at' => now()->addDays(30),
            ]);

            $user->transactions()->create([
                'type' => 'purchase',
                'amount' => $price,
                'balance_after' => $user->wallet_balance,
                'description' => ucfirst($validated['type'])." proxy — {$validated['country']}",
                'status' => 'completed',
            ]);

            return true;
        });

        if (! $purchased) {
            return redirect()->route('dashboard.wallet')
                ->with('status', 'Insufficient balance for this proxy. Add funds to your wallet and try again.');
        }

        return redirect()->route('dashboard.proxies')->with('status', 'Proxy purchased successfully for $'.$price.". It's ready in My Proxies.");
    }

    private function validateSelection(Request $request): array
    {
        $geo = config('geo');
        $types = array_keys(PricingCatalog::proxyTypes());

        $validated = $request->validate([
            'continent' => ['required', 'string'],
            'country' => ['required', 'string'],
            'type' => ['required', 'string', 'in:'.implode(',', $types)],
        ]);

        if (! isset($geo[$validated['continent']]) || ! isset($geo[$validated['continent']][$validated['country']])) {
            abort(422, 'Invalid continent/country selection.');
        }

        return $validated;
    }

    private function fakeIp(): string
    {
        return implode('.', [
            random_int(1, 223),
            random_int(0, 255),
            random_int(0, 255),
            random_int(1, 254),
        ]);
    }
}
