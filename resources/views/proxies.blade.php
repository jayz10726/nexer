@extends('layouts.app')

@section('title', 'Proxies — NEXER PROX')

@section('content')
<section class="marketing-hero">
    <div class="mx-auto max-w-7xl px-6 py-16 text-center lg:px-8">
        <p class="section-eyebrow">Proxy network</p>
        <h1 class="mt-4 text-4xl font-extrabold text-ink-900 sm:text-5xl">A Proxy Pool for Every Use Case</h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg text-gray-600">Secure, anonymous, high-speed proxies backed by a large global pool and a straightforward API.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <div class="card">
            <h3 class="text-lg font-semibold">Residential Proxies</h3>
            <p class="mt-2 text-sm text-gray-600">Real ISP-issued IPs from 150+ countries, ethically sourced for maximum trust.</p>
            <a href="{{ auth()->check() ? route('dashboard.proxies.buy') : route('register') }}" class="mt-4 inline-block text-sm font-semibold text-brand-600">Get Started →</a>
        </div>
        <div class="card">
            <h3 class="text-lg font-semibold">Mobile Proxies</h3>
            <p class="mt-2 text-sm text-gray-600">Rotating 4G/5G IPs, well suited for social media management and app testing.</p>
            <a href="{{ auth()->check() ? route('dashboard.proxies.buy') : route('register') }}" class="mt-4 inline-block text-sm font-semibold text-brand-600">Get Started →</a>
        </div>
        <div class="card">
            <h3 class="text-lg font-semibold">Datacenter Proxies</h3>
            <p class="mt-2 text-sm text-gray-600">Ultra-fast dedicated bandwidth for workloads that need raw speed at scale.</p>
            <a href="{{ auth()->check() ? route('dashboard.proxies.buy') : route('register') }}" class="mt-4 inline-block text-sm font-semibold text-brand-600">Get Started →</a>
        </div>
    </div>
</section>

<section class="bg-gray-50">
    <div class="mx-auto max-w-7xl px-6 py-16 text-center lg:px-8">
        <h2 class="text-2xl font-bold text-ink-900">Global Coverage</h2>
        <p class="mx-auto mt-3 max-w-2xl text-gray-600">Access proxies from the United States, United Kingdom, Germany, Japan, Australia, Brazil, India, and 150+ more countries.</p>
        <a href="{{ auth()->check() ? route('dashboard.proxies.buy') : route('register') }}" class="btn-primary mt-8">{{ auth()->check() ? 'Buy a Proxy Now' : 'Sign Up & Buy' }}</a>
    </div>
</section>
@endsection
