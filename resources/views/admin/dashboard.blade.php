@extends('admin.layout')

@section('title', 'Admin Dashboard — NEXER PROX')

@section('content')
<h1 class="text-2xl font-bold text-ink-900">Admin Overview</h1>
<p class="mt-1 text-sm text-gray-600">Platform-wide stats across all users.</p>

<div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5">
    <div class="card">
        <p class="text-sm text-gray-500">Total Users</p>
        <p class="mt-2 text-2xl font-bold text-ink-900">{{ $userCount }}</p>
    </div>
    <div class="card">
        <p class="text-sm text-gray-500">Admins</p>
        <p class="mt-2 text-2xl font-bold text-ink-900">{{ $adminCount }}</p>
    </div>
    <div class="card">
        <p class="text-sm text-gray-500">Total Proxies</p>
        <p class="mt-2 text-2xl font-bold text-ink-900">{{ $proxyCount }}</p>
    </div>
    <div class="card">
        <p class="text-sm text-gray-500">Total Numbers</p>
        <p class="mt-2 text-2xl font-bold text-ink-900">{{ $numberCount }}</p>
    </div>
    <div class="card">
        <p class="text-sm text-gray-500">Wallet Funds (all users)</p>
        <p class="mt-2 text-2xl font-bold gradient-text">${{ number_format($totalWalletBalance, 2) }}</p>
    </div>
</div>

<div class="mt-10 grid grid-cols-1 gap-6 lg:grid-cols-2">
    <div>
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-ink-900">Newest Users</h2>
            <a href="{{ route('admin.users') }}" class="text-sm font-semibold text-brand-600">View all</a>
        </div>
        <div class="mt-4 overflow-hidden rounded-xl border border-gray-100 bg-white">
            @forelse ($recentUsers as $user)
                <a href="{{ route('admin.users.show', $user) }}" class="flex items-center justify-between gap-4 border-b border-gray-100 px-5 py-4 last:border-0 hover:bg-gray-50">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-ink-900">{{ $user->name }}</p>
                        <p class="break-all text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                    <span class="shrink-0 rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold capitalize text-gray-600">{{ str_replace('_', ' ', $user->role) }}</span>
                </a>
            @empty
                <p class="px-5 py-6 text-center text-sm text-gray-500">No users yet.</p>
            @endforelse
        </div>
    </div>

    <div>
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-ink-900">Recent Transactions</h2>
            <a href="{{ route('admin.transactions') }}" class="text-sm font-semibold text-brand-600">View all</a>
        </div>
        <div class="mt-4 overflow-hidden rounded-xl border border-gray-100 bg-white">
            @forelse ($recentTransactions as $transaction)
                <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-5 py-4 last:border-0">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-ink-900">{{ $transaction->user->name ?? 'Deleted user' }}</p>
                        <p class="break-words text-xs text-gray-500">{{ $transaction->description ?? ucfirst($transaction->type) }}</p>
                    </div>
                    <span class="shrink-0 text-sm font-semibold {{ in_array($transaction->type, ['topup', 'bonus', 'admin_credit']) ? 'text-forest-600' : 'text-gray-700' }}">
                        {{ in_array($transaction->type, ['topup', 'bonus', 'admin_credit']) ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                    </span>
                </div>
            @empty
                <p class="px-5 py-6 text-center text-sm text-gray-500">No transactions yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
