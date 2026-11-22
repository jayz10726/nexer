<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $siteName = \App\Models\SiteSetting::query()->whereKey('site_name')->value('value') ?: 'NEXER PROX';
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/nexer-prox-mark.svg') }}">
    <title>{{ str_replace('NEXER PROX', $siteName, trim($__env->yieldContent('title', 'Admin — '.$siteName))) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-shell bg-gray-50 font-sans text-ink-900">
    <div class="flex min-h-screen">
        <aside class="admin-sidebar hidden w-64 flex-col text-gray-300 lg:flex" style="background-image: linear-gradient(180deg, #00203F 0%, #001529 100%);">
            <a href="{{ route('admin') }}" class="flex items-center gap-2 px-6 py-5 text-lg font-bold text-white">
                <img src="{{ asset('images/nexer-prox-mark.svg') }}" alt="NEXER PROX logo" class="h-8 w-8 rounded-lg object-cover ring-1 ring-white/10">
                <span class="tracking-tight">{{ $siteName }}</span>
            </a>

            @php
                $navItems = [
                    ['route' => 'admin', 'label' => 'Dashboard', 'match' => 'admin'],
                    ['route' => 'admin.users', 'label' => 'Users', 'match' => 'admin.users*'],
                    ['route' => 'admin.plans', 'label' => 'Pricing Plans', 'match' => 'admin.plans*'],
                    ['route' => 'admin.settings', 'label' => 'Site Settings', 'match' => 'admin.settings*'],
                    ['route' => 'admin.proxies', 'label' => 'Proxies', 'match' => 'admin.proxies*'],
                    ['route' => 'admin.numbers', 'label' => 'Virtual Numbers', 'match' => 'admin.numbers*'],
                    ['route' => 'admin.transactions', 'label' => 'Transactions', 'match' => 'admin.transactions*'],
                ];
                if (Auth::user()->isSuperAdmin()) {
                    $navItems[] = ['route' => 'admin.login-activity', 'label' => 'Login Activity', 'match' => 'admin.login-activity*'];
                }
            @endphp
            <nav class="flex-1 space-y-1 px-3 py-4">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs($item['match']) ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-white/10 p-4">
                <p class="truncate text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                <p class="truncate text-xs text-gray-400">{{ Auth::user()->isSuperAdmin() ? 'Super Admin' : 'Admin' }}</p>
                <div class="mt-3 flex gap-2">
                    <a href="{{ route('dashboard') }}" class="flex-1 rounded-lg border border-white/10 px-3 py-2 text-center text-xs font-semibold text-gray-300 hover:bg-white/5">My Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full rounded-lg border border-white/10 px-3 py-2 text-xs font-semibold text-gray-300 hover:bg-white/5">Log out</button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            <div class="border-b border-gray-200 bg-white lg:hidden" x-data="{ menuOpen: false }">
                <header class="flex items-center justify-between px-4 py-4 sm:px-6">
                    <a href="{{ route('admin') }}" class="text-lg font-bold">Admin Panel</a>
                    <button type="button" @click="menuOpen = !menuOpen" :aria-expanded="menuOpen.toString()" aria-controls="admin-mobile-menu" class="h-11 rounded-lg border border-gray-200 px-4 text-sm font-semibold text-ink-900">
                        <span x-text="menuOpen ? 'Close menu' : 'Menu'"></span>
                    </button>
                </header>
                <nav id="admin-mobile-menu" x-show="menuOpen" x-cloak class="space-y-1 border-t border-gray-100 px-4 py-3">
                    @foreach ($navItems as $item)
                        <a href="{{ route($item['route']) }}" class="block rounded-lg px-3 py-3 text-sm font-medium {{ request()->routeIs($item['match']) ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-3 text-sm font-medium text-gray-700">My Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit" class="w-full rounded-lg border border-gray-200 px-3 py-3 text-left text-sm font-semibold text-gray-700">Log out</button>
                    </form>
                </nav>
            </div>

            <main class="admin-main p-4 sm:p-6 lg:p-10">
                @if (session('status'))
                    <div class="mb-6 rounded-lg bg-forest-50 p-4 text-sm text-forest-700">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
