<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Proxy;
use App\Models\User;
use App\Models\VirtualNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAccountIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_accounts_start_without_shared_demo_products(): void
    {
        $this->post(route('register'), [
            'name' => 'New Customer',
            'email' => 'new-customer@example.test',
            'password' => 'TestPassword123!',
            'password_confirmation' => 'TestPassword123!',
        ])->assertRedirect(route('dashboard'));

        $user = User::where('email', 'new-customer@example.test')->firstOrFail();

        $this->assertSame(0, $user->proxies()->count());
        $this->assertSame(0, $user->virtualNumbers()->count());
    }

    public function test_purchases_and_inventory_pages_are_scoped_to_the_signed_in_user(): void
    {
        $buyer = User::factory()->create(['wallet_balance' => 100]);
        $otherUser = User::factory()->create(['wallet_balance' => 100]);

        $otherProxy = Proxy::create([
            'user_id' => $otherUser->id,
            'type' => 'datacenter',
            'ip_address' => '198.51.100.250',
            'port' => 8080,
            'username' => 'other-user',
            'password' => 'private-password',
            'country' => 'Kenya',
            'status' => 'active',
        ]);
        $otherNumber = VirtualNumber::create([
            'user_id' => $otherUser->id,
            'phone_number' => '+254 700 000 001',
            'country' => 'Kenya',
            'capability' => 'sms',
            'status' => 'active',
        ]);

        $continent = array_key_first(config('geo'));
        $country = array_key_first(config("geo.{$continent}"));

        $this->actingAs($buyer)->post(route('dashboard.proxies.store'), [
            'continent' => $continent,
            'country' => $country,
            'type' => 'datacenter',
        ])->assertRedirect(route('dashboard.proxies'));

        $this->actingAs($buyer)->post(route('dashboard.numbers.store'), [
            'continent' => $continent,
            'country' => $country,
            'service' => 'whatsapp',
        ])->assertRedirect(route('dashboard.numbers'));

        $buyerProxy = $buyer->fresh()->proxies()->firstOrFail();
        $buyerNumber = $buyer->fresh()->virtualNumbers()->firstOrFail();

        $this->actingAs($buyer)->get(route('dashboard.proxies'))
            ->assertOk()
            ->assertSee($buyerProxy->ip_address)
            ->assertDontSee($otherProxy->ip_address);
        $this->actingAs($otherUser)->get(route('dashboard.proxies'))
            ->assertOk()
            ->assertSee($otherProxy->ip_address)
            ->assertDontSee($buyerProxy->ip_address);

        $this->actingAs($buyer)->get(route('dashboard.numbers'))
            ->assertOk()
            ->assertSee($buyerNumber->phone_number)
            ->assertDontSee($otherNumber->phone_number);
        $this->actingAs($otherUser)->get(route('dashboard.numbers'))
            ->assertOk()
            ->assertSee($otherNumber->phone_number)
            ->assertDontSee($buyerNumber->phone_number);

        $this->assertSame(2, $buyer->fresh()->transactions()->count());
        $this->assertSame(0, $otherUser->fresh()->transactions()->count());
    }

    public function test_my_proxies_page_links_to_all_continents_and_preselects_the_selected_one(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard.proxies'))->assertOk();

        foreach (array_keys(config('geo')) as $continent) {
            $response->assertSee(route('dashboard.proxies.buy', ['continent' => $continent]), false);
            $response->assertSee($continent);
        }

        $this->actingAs($user)
            ->get(route('dashboard.proxies.buy', ['continent' => 'Africa']))
            ->assertOk()
            ->assertSee("continent: 'Africa'", false);

        $numbersResponse = $this->actingAs($user)->get(route('dashboard.numbers'))->assertOk();

        foreach (array_keys(config('geo')) as $continent) {
            $numbersResponse->assertSee(route('dashboard.numbers.buy', ['continent' => $continent]), false);
        }

        $this->actingAs($user)
            ->get(route('dashboard.numbers.buy', ['continent' => 'Africa']))
            ->assertOk()
            ->assertSee("continent: 'Africa'", false);
    }

    public function test_dashboard_uses_product_colored_overview_cards_and_nexer_favicon(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('dashboard-overview-proxies', false)
            ->assertSee('dashboard-overview-numbers', false)
            ->assertSee('dashboard-overview-wallet', false)
            ->assertSee('images/nexer-prox-mark.svg', false);
    }

    public function test_proxy_purchase_with_insufficient_balance_redirects_to_wallet(): void
    {
        $user = User::factory()->create(['wallet_balance' => 0]);

        $this->actingAs($user)->post(route('dashboard.proxies.store'), [
            'continent' => 'Africa',
            'country' => 'Kenya',
            'type' => 'datacenter',
        ])->assertRedirect(route('dashboard.wallet'))
            ->assertSessionHas('status', 'Insufficient balance for this proxy. Add funds to your wallet and try again.');

        $this->actingAs($user)->get(route('dashboard.wallet'))
            ->assertOk()
            ->assertSee('Insufficient balance for this proxy.');
        $this->assertSame(0, $user->fresh()->proxies()->count());
    }

    public function test_virtual_number_purchase_with_insufficient_balance_redirects_to_wallet(): void
    {
        $user = User::factory()->create(['wallet_balance' => 0]);

        $this->actingAs($user)->post(route('dashboard.numbers.store'), [
            'continent' => 'Africa',
            'country' => 'Kenya',
            'service' => 'whatsapp',
        ])->assertRedirect(route('dashboard.wallet'))
            ->assertSessionHas('status', 'Insufficient balance for this number. Add funds to your wallet and try again.');

        $this->actingAs($user)->get(route('dashboard.wallet'))
            ->assertOk()
            ->assertSee('Insufficient balance for this number.');
        $this->assertSame(0, $user->fresh()->virtualNumbers()->count());
    }

    public function test_admin_can_update_product_pricing_and_public_site_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->patch(route('admin.settings.update'), [
            'site_name' => 'NEXER Global',
            'support_email' => 'help@example.test',
            'proxy_min_price' => '8.00',
            'proxy_max_price' => '12.00',
            'number_min_price' => '7.00',
            'number_max_price' => '11.00',
        ])->assertRedirect();

        $this->assertDatabaseHas('site_settings', ['key' => 'site_name', 'value' => 'NEXER Global']);
        $this->assertDatabaseHas('site_settings', ['key' => 'proxy_min_price', 'value' => '8.00']);

        $this->actingAs($admin)->get(route('home'))
            ->assertOk()
            ->assertSee('NEXER Global')
            ->assertSee('mailto:help@example.test', false);

        $price = (float) $this->actingAs(User::factory()->create())->getJson(route('dashboard.proxies.price', [
            'continent' => 'Africa',
            'country' => 'Kenya',
            'type' => 'mobile',
        ]))->assertOk()->json('price');

        $this->assertGreaterThanOrEqual(8, $price);
        $this->assertLessThanOrEqual(12, $price);
    }

    public function test_admin_can_manage_public_pricing_plans_but_regular_users_cannot_manage_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.settings'))->assertOk();
        $this->get(route('admin.plans.create'))->assertOk()->assertSee('Create Plan');

        $this->actingAs($admin)->post(route('admin.plans.store'), [
            'name' => 'Team',
            'slug' => 'team',
            'price' => '49.00',
            'billing_period' => 'month',
            'is_custom' => '0',
            'features' => "Shared proxy access\nPriority support",
            'cta_label' => 'Choose Team',
            'is_featured' => '1',
            'sort_order' => '2',
        ])->assertRedirect(route('admin.plans'));

        $this->assertDatabaseHas('plans', [
            'slug' => 'team',
            'price' => '49.00',
            'is_featured' => true,
        ]);
        $this->assertSame(
            ['Shared proxy access', 'Priority support'],
            Plan::where('slug', 'team')->firstOrFail()->features,
        );
        $this->get(route('pricing'))->assertOk()->assertSee('Team');

        $user = User::factory()->create();
        $this->actingAs($user)->get(route('admin.settings'))->assertNotFound();
    }
}
