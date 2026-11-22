<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as GoogleUser;
use Tests\TestCase;

class GoogleAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_login_does_not_automatically_link_an_existing_email_account(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.test',
            'google_id' => null,
        ]);

        Socialite::fake('google', GoogleUser::fake([
            'id' => 'google-user-123',
            'email' => 'CUSTOMER@example.test',
            'email_verified' => true,
        ]));

        $this->get(route('auth.google.callback', ['code' => 'fake-code']))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('google');

        $this->assertGuest();
        $this->assertNull($user->fresh()->google_id);
    }

    public function test_google_login_creates_an_account_when_google_has_no_name(): void
    {
        Socialite::fake('google', GoogleUser::fake([
            'id' => 'google-user-456',
            'name' => null,
            'nickname' => null,
            'email' => 'new-customer@example.test',
            'email_verified' => true,
        ]));

        $this->get(route('auth.google.callback', ['code' => 'fake-code']))
            ->assertRedirect(route('dashboard'));

        $user = User::where('email', 'new-customer@example.test')->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertSame('new-customer', $user->name);
        $this->assertSame('google-user-456', $user->google_id);
        $this->assertNotNull($user->email_verified_at);
        $this->get(route('dashboard'))->assertOk();
    }

    public function test_google_login_rejects_an_unverified_email(): void
    {
        Socialite::fake('google', GoogleUser::fake([
            'id' => 'google-user-unverified',
            'email' => 'unverified@example.test',
            'email_verified' => false,
        ]));

        $this->get(route('auth.google.callback', ['code' => 'fake-code']))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('google');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'unverified@example.test']);
    }

    public function test_google_account_can_be_linked_after_authenticated_post_confirmation(): void
    {
        config([
            'services.google.client_id' => 'google-client-id',
            'services.google.client_secret' => 'google-client-secret',
        ]);

        $user = User::factory()->create([
            'email' => 'customer@example.test',
            'google_id' => null,
        ]);

        Socialite::fake('google', GoogleUser::fake([
            'id' => 'google-user-linked',
            'email' => 'CUSTOMER@example.test',
            'email_verified' => true,
        ]));

        $this->actingAs($user)
            ->post(route('auth.google.link'))
            ->assertRedirect('https://socialite.fake/google/authorize')
            ->assertSessionHas('google_link_user_id', $user->id);

        $this->get(route('auth.google.callback', ['code' => 'fake-code']))
            ->assertRedirect(route('dashboard.settings'))
            ->assertSessionHas('status', 'Google account connected successfully.');

        $this->assertSame('google-user-linked', $user->fresh()->google_id);
    }

    public function test_google_oauth_callback_requires_session_state(): void
    {
        config([
            'services.google.client_id' => 'google-client-id',
            'services.google.client_secret' => 'google-client-secret',
        ]);

        $this->get(route('auth.google.callback', ['code' => 'fake-code']))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('google');

        $this->assertGuest();
    }

    public function test_google_authorization_redirect_stores_oauth_state(): void
    {
        config([
            'services.google.client_id' => 'google-client-id',
            'services.google.client_secret' => 'google-client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);

        $this->get(route('auth.google'))
            ->assertRedirect()
            ->assertSessionHas('state');
    }
}
