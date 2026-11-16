@extends('dashboard.layout')

@section('title', 'Transactions — NEXER PROX')

@section('content')
<h1 class="text-2xl font-bold text-ink-900">Transactions</h1>
<p class="mt-1 text-sm text-gray-600">A full history of wallet activity on your account.</p>

<div class="mt-6 overflow-x-auto rounded-xl border border-gray-100 bg-white">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
            <tr>
                <th class="px-5 py-3">Date</th>
                <th class="px-5 py-3">Description</th>
                <th class="px-5 py-3">Type</th>
                <th class="px-5 py-3">Amount</th>
                <th class="px-5 py-3">Balance After</th>
                <th class="px-5 py-3">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($transactions as $transaction)
                <tr>
                    <td class="px-5 py-4">{{ $transaction->created_at->format('M j, Y · g:i A') }}</td>
                    <td class="px-5 py-4">{{ $transaction->description ?? '—' }}</td>
                    <td class="px-5 py-4 capitalize">{{ $transaction->type }}</td>
                    <td class="px-5 py-4 font-semibold {{ in_array($transaction->type, ['topup', 'bonus']) ? 'text-forest-600' : 'text-gray-700' }}">
                        {{ in_array($transaction->type, ['topup', 'bonus']) ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                    </td>
                    <td class="px-5 py-4">${{ number_format($transaction->balance_after, 2) }}</td>
                    <td class="px-5 py-4">
                        <span class="rounded-full px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-600">{{ ucfirst($transaction->status) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-500">No transactions yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $transactions->links() }}</div>
@endsection
