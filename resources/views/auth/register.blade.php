@extends('layouts.app')

@section('title', 'Sign Up — NEXER PROX')

@section('content')
<section class="auth-page mx-auto flex min-h-[70vh] max-w-md flex-col justify-center px-6 py-16">
    <h1 class="text-2xl font-bold text-ink-900">Create your account</h1>
    <p class="mt-2 text-sm text-gray-600">Start with a free account, upgrade to a paid plan any time.</p>

    @error('google')
        <div class="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">{{ $message }}</div>
    @enderror

    <a href="{{ route('auth.google') }}" class="mt-8 flex w-full items-center justify-center gap-3 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
        <svg class="h-5 w-5" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M23.52 12.27c0-.79-.07-1.54-.2-2.27H12v4.51h6.47c-.28 1.5-1.13 2.77-2.41 3.62v3.01h3.87c2.26-2.09 3.59-5.17 3.59-8.87z"/>
            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.07 7.93-2.9l-3.87-3.01c-1.07.72-2.44 1.15-4.06 1.15-3.13 0-5.78-2.11-6.73-4.96H1.27v3.11C3.24 21.3 7.29 24 12 24z"/>
            <path fill="#FBBC05" d="M5.27 14.28A7.14 7.14 0 0 1 4.9 12c0-.79.14-1.56.37-2.28V6.61H1.27A11.93 11.93 0 0 0 0 12c0 1.93.46 3.76 1.27 5.39l4-3.11z"/>
            <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.43-3.43C17.94 1.19 15.24 0 12 0 7.29 0 3.24 2.7 1.27 6.61l4 3.11C6.22 6.86 8.87 4.75 12 4.75z"/>
        </svg>
        Continue with Google
    </a>

    <div class="mt-6 flex items-center gap-3">
        <span class="h-px flex-1 bg-gray-200"></span>
        <span class="text-xs uppercase text-gray-400">or</span>
        <span class="h-px flex-1 bg-gray-200"></span>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" type="password" name="password" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>

        <button type="submit" class="btn-primary w-full">Sign Up</button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
        Already have an account? <a href="{{ route('login') }}" class="font-semibold text-brand-600">Log in</a>
    </p>
</section>
@endsection
