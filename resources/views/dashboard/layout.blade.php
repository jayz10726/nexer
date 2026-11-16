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
    <title>{{ str_replace('NEXER PROX', $siteName, trim($__env->yieldContent('title', 'Dashboard — '.$siteName))) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="dashboard-shell bg-gray-50 font-sans text-ink-900">
    @php
        $navItems = [
            ['route' => 'dashboard', 'label' => 'Overview', 'match' => 'dashboard'],
            ['route' => 'dashboard.proxies', 'label' => 'My Proxies', 'match' => 'dashboard.proxies'],
            ['route' => 'dashboard.numbers', 'label' => 'Virtual Numbers', 'match' => 'dashboard.numbers'],
            ['route' => 'dashboard.transactions', 'label' => 'Transactions', 'match' => 'dashboard.transactions'],
            ['route' => 'dashboard.wallet', 'label' => 'Wallet', 'match' => 'dashboard.wallet*'],
            ['route' => 'dashboard.settings', 'label' => 'Settings', 'match' => 'dashboard.settings*'],
        ];
    @endphp
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="dashboard-sidebar hidden w-64 flex-col lg:flex" style="background-image: linear-gradient(180deg, #00203F 0%, #001529 100%);">
            <a href="{{ route('home') }}" class="flex items-center gap-2 px-6 py-5 text-lg font-bold text-white">
                <img src="{{ asset('images/nexer-prox-mark.svg') }}" alt="NEXER PROX logo" class="h-8 w-8 rounded-lg object-cover ring-1 ring-white/10">
                <span class="tracking-tight">{{ $siteName }}</span>
            </a>

            <nav class="flex-1 space-y-1 px-3 py-4">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs($item['match']) ? 'bg-white/10 text-brand-300' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach

                @if (Auth::user()->isAdmin())
                    <a href="{{ route('admin') }}" class="mt-2 block rounded-lg px-3 py-2 text-center text-sm font-semibold text-white" style="background-image: linear-gradient(135deg, #00BFFF 0%, #6F2DA8 100%);">
                        Admin Panel →
                    </a>
                @endif
            </nav>

            <div class="border-t border-white/10 p-4">
                <div class="flex items-center gap-3">
                    @if (Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="" class="h-9 w-9 rounded-full ring-2 ring-brand-400/40">
                    @else
                        <span class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold text-white" style="background-image: linear-gradient(135deg, #00BFFF 0%, #6F2DA8 100%);">
                            {{ Str::substr(Auth::user()->name, 0, 1) }}
                        </span>
                    @endif
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                        <p class="truncate text-xs text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full rounded-lg border border-white/10 px-3 py-2 text-xs font-semibold text-gray-300 hover:bg-white/5 hover:text-white">Log out</button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-1">
            <div class="border-b border-gray-200 bg-white lg:hidden" x-data="{ menuOpen: false }">
                <header class="flex items-center justify-between px-6 py-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-lg font-bold">
                        <img src="{{ asset('images/nexer-prox-mark.svg') }}" alt="NEXER PROX logo" class="h-8 w-8 rounded-lg object-cover ring-1 ring-brand-200">
                        <span class="tracking-tight">{{ $siteName }}</span>
                    </a>
                    <button type="button" @click="menuOpen = !menuOpen" :aria-expanded="menuOpen.toString()" aria-controls="dashboard-mobile-menu" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-ink-900">
                        <span x-text="menuOpen ? 'Close menu' : 'Menu'"></span>
                    </button>
                </header>
                <nav id="dashboard-mobile-menu" x-show="menuOpen" x-cloak class="space-y-1 border-t border-gray-100 px-4 py-3">
                    @foreach ($navItems as $item)
                        <a href="{{ route($item['route']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs($item['match']) ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-brand-700">Admin Panel</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-left text-sm font-semibold text-gray-700">Log out</button>
                    </form>
                </nav>
            </div>

            <main class="dashboard-main p-6 lg:p-10">
                @if (session('status'))
                    <div class="mb-6 rounded-lg bg-forest-50 p-4 text-sm text-forest-700">{{ session('status') }}</div>
                @endif

                @if ($errors->has('wallet'))
                    <div class="mb-6 flex flex-col items-start gap-3 rounded-lg border border-violet-200 bg-violet-50 p-4 text-sm text-violet-800 sm:flex-row sm:items-center sm:justify-between">
                        <span>{{ $errors->first('wallet') }}</span>
                        <a href="{{ route('dashboard.wallet') }}" class="btn-primary shrink-0">Top Up Wallet</a>
                    </div>
                @endif

                @if ($errors->any() && ! $errors->has('wallet'))
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
