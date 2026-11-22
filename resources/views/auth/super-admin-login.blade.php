@extends('layouts.app')

@section('title', 'Secure Sign In — NEXER PROX')

@section('content')
<section class="auth-page mx-auto flex min-h-[70vh] max-w-md flex-col justify-center px-6 py-16">
    <h1 class="text-2xl font-bold text-ink-900">Secure sign in</h1>
    <p class="mt-2 text-sm text-gray-600">Sign in with your account credentials.</p>

    <form method="POST" action="{{ route('super-admin.authenticate') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>

        <button type="submit" class="btn-primary w-full">Sign In</button>
    </form>
</section>
@endsection
