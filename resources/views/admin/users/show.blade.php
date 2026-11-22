@extends('admin.layout')

@section('title', $user->name.' — Admin')

@section('content')
<div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="min-w-0">
        <h1 class="text-2xl font-bold text-ink-900">{{ $user->name }}</h1>
        <p class="break-all text-sm text-gray-600">{{ $user->email }} · Joined {{ $user->created_at->format('M j, Y') }}</p>
    </div>
    <a href="{{ route('admin.users') }}" class="btn-secondary shrink-0">← Back to Users</a>
</div>

<div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
    {{-- Role & status --}}
    <div class="card">
        <h2 class="text-lg font-semibold">Role &amp; Status</h2>
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-4 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500" @if(! Auth::user()->isSuperAdmin()) disabled @endif>
                    <option value="user" @selected($user->role === 'user')>User</option>
                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                    <option value="super_admin" @selected($user->role === 'super_admin')>Super Admin</option>
                </select>
                @unless (Auth::user()->isSuperAdmin())
                    <p class="mt-1 text-xs text-gray-400">Only a super admin can change roles.</p>
                @endunless
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Account Status</label>
                <select name="status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    <option value="active" @selected($user->status === 'active')>Active</option>
                    <option value="suspended" @selected($user->status === 'suspended')>Suspended</option>
                </select>
            </div>

            <button type="submit" class="btn-primary w-full">Save Changes</button>
        </form>

        @if (Auth::user()->isSuperAdmin() && $user->id !== Auth::id())
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="mt-4" onsubmit="return confirm('Delete this user permanently? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">Delete User</button>
            </form>
        @endif
    </div>

    {{-- Wallet --}}
    <div class="card">
        <h2 class="text-lg font-semibold">Wallet</h2>
        <p class="mt-2 text-3xl font-bold text-ink-900">${{ number_format($user->wallet_balance, 2) }}</p>

        <form method="POST" action="{{ route('admin.users.wallet-adjust', $user) }}" class="mt-4 space-y-3">
            @csrf
            <label class="block text-sm font-medium text-gray-700">Adjust balance (USD)</label>
            <input type="number" step="0.01" name="amount" required placeholder="e.g. 20 or -10"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            <input type="text" name="note" placeholder="Reason (optional)"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            <button type="submit" class="btn-primary w-full">Apply Adjustment</button>
            <p class="text-xs text-gray-400">Use a positive number to credit, negative to debit.</p>
        </form>
    </div>

    {{-- Resources summary --}}
    <div class="card">
        <h2 class="text-lg font-semibold">Resources</h2>
        <dl class="mt-4 space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Proxies</dt><dd class="font-semibold">{{ $user->proxies->count() }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Virtual Numbers</dt><dd class="font-semibold">{{ $user->virtualNumbers->count() }}</dd></div>
        </dl>
        <div class="mt-4 flex flex-col gap-2">
            <a href="{{ route('admin.proxies.create') }}" class="btn-secondary text-center">Assign Proxy</a>
            <a href="{{ route('admin.numbers.create') }}" class="btn-secondary text-center">Assign Number</a>
        </div>
    </div>
</div>

{{-- Transactions --}}
<div class="mt-10">
    <h2 class="text-lg font-semibold text-ink-900">Recent Transactions</h2>
    <div class="mt-4 overflow-hidden rounded-xl border border-gray-100 bg-white">
        @forelse ($transactions as $transaction)
            <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-5 py-4 last:border-0">
                <div class="min-w-0">
                    <p class="break-words text-sm font-medium text-ink-900">{{ $transaction->description ?? ucfirst($transaction->type) }}</p>
                    <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M j, Y · g:i A') }}</p>
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
@endsection
