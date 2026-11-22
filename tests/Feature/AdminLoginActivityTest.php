<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as GoogleUser;
use Tests\TestCase;

class AdminLoginActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_password_login_is_recorded(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.test',
            'password' => Hash::make('ValidPassword123!'),
        ]);

        $this->post(route('login'), [
            'email' => 'customer@example.test',
            'password' => 'ValidPassword123!',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('login_activities', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_suspended_user_login_is_not_recorded(): void
    {
        User::factory()->create([
            'email' => 'suspended@example.test',
            'password' => Hash::make('ValidPassword123!'),
            'status' => 'suspended',
        ]);

        $this->from(route('login'))->post(route('login'), [
            'email' => 'suspended@example.test',
            'password' => 'ValidPassword123!',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseCount('login_activities', 0);
    }

    public function test_successful_google_login_is_recorded(): void
    {
        Socialite::fake('google', GoogleUser::fake([
            'id' => 'google-user-789',
            'email' => 'google-customer@example.test',
            'email_verified' => true,
        ]));

        $this->get(route('auth.google.callback', ['code' => 'fake-code']))
            ->assertRedirect(route('dashboard'));

        $user = User::where('email', 'google-customer@example.test')->firstOrFail();

        $this->assertDatabaseHas('login_activities', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public function test_super_admin_can_view_recent_logins_and_active_sessions(): void
    {
        config(['session.driver' => 'database']);

        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $activeUser = User::factory()->create([
            'name' => 'Active Customer',
            'email' => 'active@example.test',
        ]);
        $expiredUser = User::factory()->create([
            'name' => 'Expired Customer',
            'email' => 'expired@example.test',
        ]);

        DB::table('sessions')->insert([
            [
                'id' => 'active-session',
                'user_id' => $activeUser->id,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Test Browser',
                'payload' => 'payload',
                'last_activity' => now()->timestamp,
            ],
            [
                'id' => 'expired-session',
                'user_id' => $expiredUser->id,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Test Browser',
                'payload' => 'payload',
                'last_activity' => now()->subMinutes(200)->timestamp,
            ],
        ]);

        $this->actingAs($superAdmin)
            ->get(route('admin.login-activity'))
            ->assertOk()
            ->assertSee('active@example.test')
            ->assertDontSee('expired@example.test');
    }

    public function test_regular_admin_cannot_view_login_activity(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.login-activity'))
            ->assertNotFound();
    }
}
