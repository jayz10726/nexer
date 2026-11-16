@extends('layouts.app')

@section('title', 'Pricing — NEXER PROX')

@section('content')
<section class="marketing-hero">
    <div class="mx-auto max-w-7xl px-6 py-16 text-center lg:px-8">
        <p class="section-eyebrow">Pricing</p>
        <h1 class="mt-4 text-4xl font-extrabold text-ink-900 sm:text-5xl">Flexible Plans for Every Stage</h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg text-gray-600">Choose the plan that fits your business today. Upgrade any time as you grow.</p>
    </div>
</section>

{{-- Clarify plans vs. pay-as-you-go marketplace so users don't get stuck picking a plan when they just want one proxy/number --}}
<section class="mx-auto max-w-4xl px-6 lg:px-8">
    <div class="card flex flex-col items-center gap-4 text-center sm:flex-row sm:text-left">
        <span class="brand-mark shrink-0 text-lg">💡</span>
        <div class="flex-1">
            <p class="text-sm font-semibold text-ink-900">Just need one proxy or number, not a plan?</p>
            <p class="mt-1 text-sm text-gray-600">Skip the plans below — buy exactly what you need, priced individually, from your dashboard.</p>
        </div>
        @auth
            <a href="{{ route('dashboard.proxies.buy') }}" class="btn-primary shrink-0">Buy Individually</a>
        @else
            <a href="{{ route('register') }}" class="btn-primary shrink-0">Sign Up &amp; Buy</a>
        @endauth
    </div>
</section>

<section class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        @forelse ($plans as $plan)
            <div class="card flex flex-col {{ $plan->is_featured ? 'ring-2 ring-brand-600' : '' }}">
                @if ($plan->is_featured)
                    <span class="mb-3 inline-block w-fit rounded-full bg-brand-100 px-3 py-1 text-xs font-semibold text-brand-700">Most Popular</span>
                @endif
                <h3 class="text-lg font-semibold">{{ $plan->name }}</h3>
                <p class="mt-2 text-3xl font-bold text-ink-900">
                    @if ($plan->is_custom)
                        Custom
                    @else
                        ${{ number_format($plan->price, 0) }}<span class="text-base font-medium text-gray-500">/{{ $plan->billing_period }}</span>
                    @endif
                </p>
                <ul class="mt-6 flex-1 space-y-3 text-sm text-gray-700">
                    @foreach ($plan->features as $feature)
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 text-brand-600">✓</span>
                            <span>{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}" class="{{ $plan->is_featured ? 'btn-primary' : 'btn-secondary' }} mt-8">{{ $plan->cta_label }}</a>
            </div>
        @empty
            <p class="col-span-3 text-center text-gray-500">Plans haven't been seeded yet — run <code>php artisan db:seed</code>.</p>
        @endforelse
    </div>
</section>

<section class="bg-ink-900 text-white">
    <div class="mx-auto max-w-7xl px-6 py-10 text-center text-sm text-gray-300 lg:px-8">
        100% Anonymous · 150+ Countries · 99.9% Uptime · 24/7 Support
    </div>
</section>
@endsection
