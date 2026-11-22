<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\SuperAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as GoogleUser;
use RuntimeException;
use Tests\TestCase;

class AdminSecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_login_page_does_not_disclose_admin_access(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertDontSee('administrator')
            ->assertDontSee('super admin')
            ->assertDontSee('/admin')
            ->assertDontSee('/super-admin');
    }

    public function test_super_admin_can_sign_in_from_the_private_entry(): void
    {
        $superAdmin = User::factory()->create([
            'email' => 'operator@example.test',
            'password' => Hash::make('ValidPassword123!'),
            'role' => 'super_admin',
        ]);

        $this->from(route('super-admin.login'))->post(route('super-admin.authenticate'), [
            'email' => 'operator@example.test',
            'password' => 'ValidPassword123!',
        ])->assertRedirect(route('admin'));

        $this->assertAuthenticatedAs($superAdmin);
        $this->assertDatabaseHas('login_activities', ['user_id' => $superAdmin->id]);
        $this->get(route('admin'))->assertOk()->assertSee('Admin Overview');
    }

    public function test_private_entry_page_renders_without_a_public_login_link(): void
    {
        $this->get(route('super-admin.login'))
            ->assertOk()
            ->assertSee('Secure sign in')
            ->assertSee(route('super-admin.authenticate'), false);

        $this->get(route('login'))
            ->assertOk()
            ->assertDontSee(route('super-admin.login'));
    }

    public function test_super_admin_cannot_sign_in_through_the_public_login(): void
    {
        User::factory()->create([
            'email' => 'operator@example.test',
            'password' => Hash::make('ValidPassword123!'),
            'role' => 'super_admin',
        ]);

        $this->from(route('login'))->post(route('login'), [
            'email' => 'operator@example.test',
            'password' => 'ValidPassword123!',
        ])->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertDatabaseCount('login_activities', 0);
    }

    public function test_private_entry_rejects_non_super_admin_accounts(): void
    {
        User::factory()->create([
            'email' => 'customer@example.test',
            'password' => Hash::make('ValidPassword123!'),
        ]);

        $this->from(route('super-admin.login'))->post(route('super-admin.authenticate'), [
            'email' => 'customer@example.test',
            'password' => 'ValidPassword123!',
        ])->assertRedirect(route('super-admin.login'));

        $this->assertGuest();
    }

    public function test_regular_admin_cannot_suspend_or_adjust_the_wallet_of_a_privileged_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'wallet_balance' => 100,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.users.update', $superAdmin), [
                'role' => 'super_admin',
                'status' => 'suspended',
            ])
            ->assertNotFound();

        $this->actingAs($admin)
            ->post(route('admin.users.wallet-adjust', $superAdmin), [
                'amount' => '-50',
                'note' => 'test',
            ])
            ->assertNotFound();

        $this->assertSame('active', $superAdmin->fresh()->status);
        $this->assertSame('100.00', $superAdmin->fresh()->wallet_balance);
        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_super_admin_cannot_sign_in_through_google(): void
    {
        User::factory()->create([
            'email' => 'operator@example.test',
            'google_id' => 'google-operator-123',
            'role' => 'super_admin',
        ]);

        Socialite::fake('google', GoogleUser::fake([
            'id' => 'google-operator-123',
            'email' => 'operator@example.test',
            'email_verified' => true,
        ]));

        $this->get(route('auth.google.callback', ['code' => 'fake-code']))
            ->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertDatabaseCount('login_activities', 0);
    }

    public function test_password_login_attempts_are_rate_limited(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->from(route('login'))->post(route('login'), [
                'email' => 'unknown@example.test',
                'password' => 'InvalidPassword123!',
            ])->assertRedirect(route('login'));
        }

        $this->from(route('login'))->post(route('login'), [
            'email' => 'unknown@example.test',
            'password' => 'InvalidPassword123!',
        ])->assertStatus(429);
    }

    public function test_seeder_rejects_known_placeholder_credentials(): void
    {
        config([
            'admin.super_admin.email' => 'operator@example.test',
            'admin.super_admin.password' => 'short',
        ]);

        $this->expectException(RuntimeException::class);
        $this->seed(SuperAdminSeeder::class);
    }

    public function test_seeder_creates_initial_privileged_account_from_private_configuration(): void
    {
        config([
            'admin.super_admin.name' => 'Operations User',
            'admin.super_admin.email' => 'operator@example.test',
            'admin.super_admin.password' => 'UniqueBootstrapPassword123!',
        ]);

        $this->seed(SuperAdminSeeder::class);

        $user = User::where('email', 'operator@example.test')->firstOrFail();

        $this->assertSame('Operations User', $user->name);
        $this->assertSame('super_admin', $user->role);
        $this->assertTrue(Hash::check('UniqueBootstrapPassword123!', $user->password));
    }

    public function test_seeder_does_not_elevate_an_existing_regular_account(): void
    {
        $user = User::factory()->create([
            'email' => 'operator@example.test',
            'role' => 'user',
        ]);

        config([
            'admin.super_admin.email' => 'operator@example.test',
            'admin.super_admin.password' => 'NewUniquePassword456!',
        ]);

        try {
            $this->seed(SuperAdminSeeder::class);
            $this->fail('The seeder should reject an email already assigned to a regular account.');
        } catch (RuntimeException) {
            $this->assertSame('user', $user->fresh()->role);
        }
    }

    public function test_seeder_does_not_reset_an_existing_privileged_account(): void
    {
        $user = User::factory()->create([
            'email' => 'operator@example.test',
            'password' => Hash::make('ExistingUniquePassword123!'),
            'role' => 'super_admin',
        ]);

        config([
            'admin.super_admin.email' => 'operator@example.test',
            'admin.super_admin.password' => 'NewUniquePassword456!',
        ]);

        $this->seed(SuperAdminSeeder::class);

        $this->assertTrue(Hash::check('ExistingUniquePassword123!', $user->fresh()->password));
    }
}
