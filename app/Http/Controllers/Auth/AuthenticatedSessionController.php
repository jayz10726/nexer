<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function createSuperAdmin(): View
    {
        return view('auth.super-admin-login');
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->authenticate($request);
    }

    public function storeSuperAdmin(Request $request): RedirectResponse
    {
        return $this->authenticate($request, true);
    }

    private function authenticate(Request $request, bool $superAdminOnly = false): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, ! $superAdminOnly && $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();
        $roleMismatch = $superAdminOnly
            ? ! $user->isSuperAdmin()
            : $user->isSuperAdmin();

        if ($roleMismatch || $user->isSuspended()) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => $superAdminOnly || $roleMismatch
                    ? __('auth.failed')
                    : 'This account has been suspended. Contact support for help.',
            ]);
        }

        $request->session()->regenerate();
        LoginActivity::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'logged_in_at' => now(),
        ]);

        return redirect()->intended($superAdminOnly ? route('admin') : route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
