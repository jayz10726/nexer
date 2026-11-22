<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WalletTopUp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WalletTopUpSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_paystack_callback_credits_once_and_is_idempotent(): void
    {
        config(['services.paystack.secret_key' => 'paystack-secret']);

        $user = User::factory()->create(['wallet_balance' => 0]);
        $topUp = $this->createTopUp($user);
        Http::fake([
            'api.paystack.co/transaction/verify/*' => Http::response([
                'status' => true,
                'data' => $this->verifiedPayment($user, $topUp),
            ]),
        ]);

        $this->actingAs($user)
            ->get(route('dashboard.wallet.callback', ['reference' => $topUp->reference]))
            ->assertRedirect(route('dashboard.wallet'))
            ->assertSessionHas('status', 'Payment confirmed. Your wallet has been credited.');

        $this->assertSame('5.00', $user->fresh()->wallet_balance);
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'gateway_reference' => $topUp->reference,
            'amount' => '5.00',
        ]);
        $this->assertSame('credited', $topUp->fresh()->status);

        $this->get(route('dashboard.wallet.callback', ['reference' => $topUp->reference]))
            ->assertRedirect(route('dashboard.wallet'))
            ->assertSessionHas('status', 'This payment was already added to your wallet.');

        $this->assertSame('5.00', $user->fresh()->wallet_balance);
        $this->assertDatabaseCount('transactions', 1);
    }

    public function test_paystack_reference_cannot_credit_a_different_users_wallet(): void
    {
        config(['services.paystack.secret_key' => 'paystack-secret']);

        $owner = User::factory()->create(['wallet_balance' => 0]);
        $attacker = User::factory()->create(['wallet_balance' => 0]);
        $topUp = $this->createTopUp($owner);

        $this->actingAs($attacker)
            ->get(route('dashboard.wallet.callback', ['reference' => $topUp->reference]))
            ->assertRedirect(route('dashboard.wallet'))
            ->assertSessionHasErrors('payment');

        $this->assertSame('0.00', $owner->fresh()->wallet_balance);
        $this->assertSame('0.00', $attacker->fresh()->wallet_balance);
        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_paystack_callback_rejects_a_mismatched_amount_or_currency(): void
    {
        config(['services.paystack.secret_key' => 'paystack-secret']);

        $user = User::factory()->create(['wallet_balance' => 0]);
        $topUp = $this->createTopUp($user);
        $payment = $this->verifiedPayment($user, $topUp);
        $payment['amount']++;
        $payment['currency'] = 'USD';

        Http::fake([
            'api.paystack.co/transaction/verify/*' => Http::response([
                'status' => true,
                'data' => $payment,
            ]),
        ]);

        $this->actingAs($user)
            ->get(route('dashboard.wallet.callback', ['reference' => $topUp->reference]))
            ->assertRedirect(route('dashboard.wallet'))
            ->assertSessionHasErrors('payment');

        $this->assertSame('0.00', $user->fresh()->wallet_balance);
        $this->assertSame('pending', $topUp->fresh()->status);
        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_top_up_initialization_persists_server_owned_payment_intent(): void
    {
        config(['services.paystack.secret_key' => 'paystack-secret']);

        $user = User::factory()->create();
        Http::fake([
            'api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'data' => ['authorization_url' => 'https://checkout.paystack.com/test'],
            ]),
        ]);

        $this->actingAs($user)
            ->post(route('dashboard.wallet.topup'), [
                'amount' => '5.00',
                'payment_method' => 'card',
            ])
            ->assertRedirect('https://checkout.paystack.com/test');

        $topUp = WalletTopUp::firstOrFail();

        $this->assertSame($user->id, $topUp->user_id);
        $this->assertSame('5.00', $topUp->wallet_amount);
        $this->assertSame(65000, $topUp->gateway_amount);
        $this->assertSame('KES', $topUp->currency);
        $this->assertSame('pending', $topUp->status);

        Http::assertSent(fn ($request) => $request['reference'] === $topUp->reference
            && $request['metadata']['user_id'] === $user->id
            && $request['metadata']['purpose'] === 'wallet_topup');
    }

    private function createTopUp(User $user): WalletTopUp
    {
        return WalletTopUp::create([
            'user_id' => $user->id,
            'reference' => 'WZ-AAAAAAAAAAAAAAAAAA',
            'wallet_amount' => '5.00',
            'currency' => 'KES',
            'gateway_amount' => 65000,
            'usd_to_paystack_rate' => 130,
            'status' => 'pending',
        ]);
    }

    private function verifiedPayment(User $user, WalletTopUp $topUp): array
    {
        return [
            'status' => 'success',
            'reference' => $topUp->reference,
            'amount' => $topUp->gateway_amount,
            'currency' => $topUp->currency,
            'customer' => ['email' => $user->email],
            'metadata' => [
                'user_id' => $user->id,
                'purpose' => 'wallet_topup',
                'wallet_amount_usd' => $topUp->wallet_amount,
                'usd_to_paystack_rate' => $topUp->usd_to_paystack_rate,
            ],
        ];
    }
}
