<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as GoogleUser;
use Throwable;

class GoogleController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return redirect()->route('login')->withErrors([
                'google' => 'Google sign-in is not configured yet. Add GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET to the environment.',
            ]);
        }

        $request->session()->forget('google_link_user_id');

        return Socialite::driver('google')->redirect();
    }

    public function beginLink(Request $request): RedirectResponse
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return back()->withErrors([
                'google' => 'Google sign-in is not configured yet. Add GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET to the environment.',
            ]);
        }

        if ($request->user()->isSuperAdmin()) {
            return back()->withErrors([
                'google' => 'Google sign-in cannot be linked to this account.',
            ]);
        }

        $request->session()->put('google_link_user_id', $request->user()->id);

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        $linkingUserId = $request->session()->pull('google_link_user_id');

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException) {
            return $this->googleErrorRedirect($request, $linkingUserId !== null);
        } catch (Throwable $exception) {
            report($exception);

            return $this->googleErrorRedirect($request, $linkingUserId !== null);
        }

        if (! $googleUser->getId() || ! $this->hasVerifiedEmail($googleUser)) {
            return $this->googleErrorRedirect(
                $request,
                $linkingUserId !== null,
                'Google must provide a verified email address to continue.'
            );
        }

        $email = Str::lower(trim($googleUser->getEmail()));
        if ($linkingUserId !== null) {
            return $this->linkGoogleAccount($request, $googleUser, (int) $linkingUserId);
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if ($user?->isSuperAdmin()) {
            return redirect()->route('login')->withErrors([
                'email' => __('auth.failed'),
            ]);
        }

        if (! $user && User::whereRaw('LOWER(email) = ?', [$email])->exists()) {
            return redirect()->route('login')->withErrors([
                'google' => 'Sign in with your existing account, then connect Google from Settings.',
            ]);
        }

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName()
                    ?: $googleUser->getNickname()
                    ?: Str::before($email, '@'),
                'email' => $email,
                'password' => Hash::make(Str::random(32)),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
            ]);
        }

        if ($user->isSuspended()) {
            return redirect()->route('login')->withErrors([
                'email' => 'This account has been suspended. Contact support for help.',
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();
        LoginActivity::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'logged_in_at' => now(),
        ]);

        return redirect()->intended(route('dashboard'));
    }

    private function linkGoogleAccount(Request $request, GoogleUser $googleUser, int $linkingUserId): RedirectResponse
    {
        $user = $request->user();

        if (! $user || $user->id !== $linkingUserId || $user->isSuperAdmin() || $user->isSuspended()) {
            return $this->googleErrorRedirect($request, false);
        }

        if (Str::lower($user->email) !== Str::lower(trim((string) $googleUser->getEmail()))) {
            return $this->googleErrorRedirect(
                $request,
                true,
                'Connect a Google account that uses the same verified email address.'
            );
        }

        $linkedUser = User::where('google_id', $googleUser->getId())->first();

        if (($linkedUser && $linkedUser->id !== $user->id)
            || ($user->google_id && $user->google_id !== $googleUser->getId())) {
            return $this->googleErrorRedirect(
                $request,
                true,
                'This Google account is already linked to another account.'
            );
        }

        $user->forceFill([
            'google_id' => $googleUser->getId(),
            'avatar' => $user->avatar ?: $googleUser->getAvatar(),
        ])->save();

        return redirect()->route('dashboard.settings')->with('status', 'Google account connected successfully.');
    }

    private function hasVerifiedEmail(GoogleUser $googleUser): bool
    {
        return is_string($googleUser->getEmail())
            && filter_var($googleUser->getEmail(), FILTER_VALIDATE_EMAIL)
            && ($googleUser->getRaw()['email_verified'] ?? $googleUser->getRaw()['verified_email'] ?? false) === true;
    }

    private function googleErrorRedirect(
        Request $request,
        bool $linking,
        string $message = 'Google sign-in could not be completed. Please try again.'
    ): RedirectResponse {
        $route = $linking && $request->user() ? 'dashboard.settings' : 'login';

        return redirect()->route($route)->withErrors(['google' => $message]);
    }
}
