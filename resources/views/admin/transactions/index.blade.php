@extends('admin.layout')

@section('title', 'Transactions — Admin')

@section('content')
<h1 class="text-2xl font-bold text-ink-900">All Transactions</h1>

<form method="GET" class="mt-6 flex flex-wrap gap-3">
    <select name="type" class="rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        <option value="">All types</option>
        @foreach (['topup', 'bonus', 'purchase', 'admin_credit', 'admin_debit'] as $type)
            <option value="{{ $type }}" @selected(request('type') === $type)>{{ str_replace('_', ' ', ucfirst($type)) }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-secondary">Filter</button>
    @if (request('type'))
        <a href="{{ route('admin.transactions') }}" class="btn-secondary">Clear</a>
    @endif
</form>

<div class="mt-6 overflow-x-auto rounded-xl border border-gray-100 bg-white">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
            <tr>
                <th class="px-5 py-3">User</th>
                <th class="px-5 py-3">Date</th>
                <th class="px-5 py-3">Description</th>
                <th class="px-5 py-3">Type</th>
                <th class="px-5 py-3">Amount</th>
                <th class="px-5 py-3">Balance After</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($transactions as $transaction)
                <tr>
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.users.show', $transaction->user) }}" class="font-medium text-brand-600">{{ $transaction->user->name ?? 'Deleted user' }}</a>
                    </td>
                    <td class="px-5 py-4 text-gray-500">{{ $transaction->created_at->format('M j, Y · g:i A') }}</td>
                    <td class="px-5 py-4">{{ $transaction->description ?? '—' }}</td>
                    <td class="px-5 py-4 capitalize">{{ str_replace('_', ' ', $transaction->type) }}</td>
                    <td class="px-5 py-4 font-semibold {{ in_array($transaction->type, ['topup', 'bonus', 'admin_credit']) ? 'text-forest-600' : 'text-gray-700' }}">
                        {{ in_array($transaction->type, ['topup', 'bonus', 'admin_credit']) ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                    </td>
                    <td class="px-5 py-4">${{ number_format($transaction->balance_after, 2) }}</td>
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
