@extends('dashboard.layout')

@section('title', 'Overview — NEXER PROX')

@section('content')
<h1 class="text-2xl font-bold text-ink-900">Welcome back, {{ Auth::user()->name }}</h1>
<p class="mt-1 text-sm text-gray-600">Here's a quick look at your account.</p>

<div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <div class="dashboard-overview-card dashboard-overview-proxies">
        <div class="dashboard-overview-icon">IP</div>
        <p class="mt-5 text-xs font-bold uppercase tracking-[0.16em] text-white/75">Global IP access</p>
        <p class="mt-1 text-sm font-semibold text-white">My Proxies</p>
        <p class="mt-2 text-4xl font-extrabold tracking-tight text-white">{{ $proxyCount }}</p>
        @if ($proxyCount > 0)
            <a href="{{ route('dashboard.proxies') }}" class="dashboard-overview-link">View proxies <span aria-hidden="true">→</span></a>
        @else
            <a href="{{ route('dashboard.proxies.buy') }}" class="dashboard-overview-link">Buy your first proxy <span aria-hidden="true">→</span></a>
        @endif
    </div>
    <div class="dashboard-overview-card dashboard-overview-numbers">
        <div class="dashboard-overview-icon">#</div>
        <p class="mt-5 text-xs font-bold uppercase tracking-[0.16em] text-white/75">Local presence</p>
        <p class="mt-1 text-sm font-semibold text-white">My Virtual Numbers</p>
        <p class="mt-2 text-4xl font-extrabold tracking-tight text-white">{{ $numberCount }}</p>
        @if ($numberCount > 0)
            <a href="{{ route('dashboard.numbers') }}" class="dashboard-overview-link">View numbers <span aria-hidden="true">→</span></a>
        @else
            <a href="{{ route('dashboard.numbers.buy') }}" class="dashboard-overview-link">Get your first number <span aria-hidden="true">→</span></a>
        @endif
    </div>
    <div class="dashboard-overview-card dashboard-overview-wallet">
        <div class="dashboard-overview-icon">$</div>
        <p class="mt-5 text-xs font-bold uppercase tracking-[0.16em] text-white/75">Ready to spend</p>
        <p class="mt-1 text-sm font-semibold text-white">Wallet Balance</p>
        <p class="mt-2 text-3xl font-extrabold tracking-tight text-white">${{ number_format($walletBalance, 2) }}</p>
        <a href="{{ route('dashboard.wallet') }}" class="dashboard-overview-link">Add funds <span aria-hidden="true">→</span></a>
    </div>
    <div class="dashboard-overview-card dashboard-overview-actions">
        <div class="dashboard-overview-icon">↗</div>
        <p class="mt-5 text-xs font-bold uppercase tracking-[0.16em] text-white/60">Ready when you are</p>
        <p class="mt-1 text-sm font-semibold text-white">What do you need?</p>
        <div class="mt-4 flex flex-col gap-3">
            <a href="{{ route('dashboard.proxies.buy') }}" class="dashboard-overview-link">Buy a Proxy <span aria-hidden="true">→</span></a>
            <a href="{{ route('dashboard.numbers.buy') }}" class="dashboard-overview-link">Get a Number <span aria-hidden="true">→</span></a>
        </div>
    </div>
</div>

@if ($proxyCount === 0 && $numberCount === 0)
    <div class="mt-6 flex flex-col items-center justify-between gap-4 rounded-xl border border-brand-200 bg-brand-50 p-5 text-center sm:flex-row sm:text-left">
        <div>
            <p class="text-sm font-semibold text-ink-900">You haven't bought anything yet</p>
            <p class="mt-1 text-sm text-gray-600">It only takes 3 clicks — pick a country, pick a type, and you're set.</p>
        </div>
        <a href="{{ route('dashboard.proxies.buy') }}" class="btn-primary shrink-0">Buy Your First Proxy</a>
    </div>
@endif

<div class="mt-10">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-ink-900">Recent Transactions</h2>
        <a href="{{ route('dashboard.transactions') }}" class="text-sm font-semibold text-brand-600">View all</a>
    </div>

    <div class="mt-4 overflow-hidden rounded-xl border border-gray-100 bg-white">
        @forelse ($recentTransactions as $transaction)
            <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-5 py-4 last:border-0">
                <div class="min-w-0">
                    <p class="break-words text-sm font-medium text-ink-900">{{ $transaction->description ?? ucfirst($transaction->type) }}</p>
                    <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M j, Y · g:i A') }}</p>
                </div>
                <span class="shrink-0 text-sm font-semibold {{ in_array($transaction->type, ['topup', 'bonus']) ? 'text-forest-600' : 'text-gray-700' }}">
                    {{ in_array($transaction->type, ['topup', 'bonus']) ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                </span>
            </div>
        @empty
            <p class="px-5 py-6 text-center text-sm text-gray-500">No transactions yet.</p>
        @endforelse
    </div>
</div>
@endsection
