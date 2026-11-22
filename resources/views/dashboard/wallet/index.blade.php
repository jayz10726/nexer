@extends('dashboard.layout')

@section('title', 'Wallet — NEXER PROX')

@section('content')
<h1 class="text-2xl font-bold text-ink-900">Wallet</h1>

<div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="card lg:col-span-1">
        <p class="text-sm text-gray-500">Current Balance</p>
        <p class="mt-2 text-3xl font-bold gradient-text">${{ number_format($walletBalance, 2) }}</p>

        <form method="POST" action="{{ route('dashboard.wallet.topup') }}" class="mt-6 space-y-3" x-data="{ amount: '', paymentMethod: 'card', rate: {{ config('currency.paystack.usd_rate') }} }">
            @csrf
            <label class="block text-sm font-medium text-gray-700">Quick top up</label>
            <div class="grid grid-cols-4 gap-2">
                @foreach ([5, 10, 25, 50] as $preset)
                    <button type="button" @click="amount = '{{ $preset }}'"
                        class="rounded-lg border px-2 py-2 text-sm font-semibold transition"
                        :class="amount === '{{ $preset }}' ? 'border-brand-600 bg-brand-50 text-brand-700' : 'border-gray-200 text-gray-600 hover:border-brand-300'">
                        ${{ $preset }}
                    </button>
                @endforeach
            </div>

            <label for="amount" class="block text-sm font-medium text-gray-700">Or enter a custom amount (USD)</label>
            <input type="number" step="0.01" min="5" name="amount" id="amount" required x-model="amount"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                placeholder="e.g. 25.00">
            <p x-show="Number(amount) >= 5" x-cloak class="text-xs text-gray-500">
                You will pay approximately <span class="font-semibold text-ink-900" x-text="'{{ config('currency.paystack.code') }} ' + (Number(amount) * rate).toFixed(2)"></span> at the current rate of $1 = {{ config('currency.paystack.code') }} {{ number_format(config('currency.paystack.usd_rate'), 2) }}.
            </p>
            @error('amount')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <label for="payment_method" class="block text-sm font-medium text-gray-700">Choose how to pay</label>
            <select name="payment_method" id="payment_method" required x-model="paymentMethod"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                <option value="card">Card</option>
                <option value="bank">Bank account</option>
                <option value="apple_pay">Apple Pay</option>
                <option value="ussd">USSD</option>
                <option value="qr">QR code</option>
                <option value="mobile_money">Mobile money</option>
                <option value="bank_transfer">Bank transfer</option>
            </select>
            @error('payment_method')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('payment')
                <p class="rounded-lg bg-red-50 p-3 text-sm text-red-700">{{ $message }}</p>
            @enderror
            <button type="submit" class="btn-primary w-full">Continue to secure payment</button>
            <p class="text-xs text-gray-400">Paystack will securely collect your payment details and PIN when required. Your details never pass through this app.</p>
        </form>
    </div>

    <div class="card lg:col-span-2">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Recent Activity</h2>
            <a href="{{ route('dashboard.transactions') }}" class="text-sm font-semibold text-brand-600">View all</a>
        </div>

        <div class="mt-4 divide-y divide-gray-100">
            @forelse ($transactions as $transaction)
                <div class="flex items-center justify-between gap-4 py-3">
                    <div class="min-w-0">
                        <p class="break-words text-sm font-medium text-ink-900">{{ $transaction->description ?? ucfirst($transaction->type) }}</p>
                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M j, Y · g:i A') }}</p>
                    </div>
                    <span class="shrink-0 text-sm font-semibold {{ in_array($transaction->type, ['topup', 'bonus']) ? 'text-forest-600' : 'text-gray-700' }}">
                        {{ in_array($transaction->type, ['topup', 'bonus']) ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                    </span>
                </div>
            @empty
                <p class="py-6 text-center text-sm text-gray-500">No wallet activity yet.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $transactions->links() }}</div>
    </div>
</div>
@endsection
