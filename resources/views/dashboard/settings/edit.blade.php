@extends('dashboard.layout')

@section('title', 'Settings — NEXER PROX')

@section('content')
<h1 class="text-2xl font-bold text-ink-900">Settings</h1>
<p class="mt-1 text-sm text-gray-600">Update your profile details and password.</p>

<div class="mt-6 max-w-xl card">
    <form method="POST" action="{{ route('dashboard.settings.update') }}" class="space-y-5">
        @csrf
        @method('PATCH')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <hr class="border-gray-100">

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" name="password" id="password"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                placeholder="Leave blank to keep current password">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>

        <button type="submit" class="btn-primary">Save Changes</button>
    </form>
</div>

<section class="mt-6 max-w-xl card">
    <h2 class="text-lg font-semibold text-ink-900">Google sign-in</h2>
    @if ($user->isSuperAdmin())
        <p class="mt-2 text-sm text-gray-600">Google sign-in is unavailable for this account.</p>
    @elseif ($user->google_id)
        <p class="mt-2 text-sm text-forest-700">A Google account is connected to this account.</p>
    @else
        <p class="mt-2 text-sm text-gray-600">Connect the Google account that uses your verified email address.</p>
        <form method="POST" action="{{ route('auth.google.link') }}" class="mt-4">
            @csrf
            <button type="submit" class="btn-secondary">Connect Google account</button>
        </form>
    @endif
</section>
@endsection
