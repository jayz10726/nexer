@extends('admin.layout')

@section('title', 'Users — Admin')

@section('content')
<h1 class="text-2xl font-bold text-ink-900">Users</h1>

<form method="GET" class="mt-6 flex flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..."
        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:w-64">
    <select name="role" class="rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        <option value="">All roles</option>
        <option value="user" @selected(request('role') === 'user')>User</option>
        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
        <option value="super_admin" @selected(request('role') === 'super_admin')>Super Admin</option>
    </select>
    <button type="submit" class="btn-secondary">Filter</button>
    @if (request('search') || request('role'))
        <a href="{{ route('admin.users') }}" class="btn-secondary">Clear</a>
    @endif
</form>

<div class="mt-6 overflow-x-auto rounded-xl border border-gray-100 bg-white">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
            <tr>
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Email</th>
                <th class="px-5 py-3">Role</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Wallet</th>
                <th class="px-5 py-3">Joined</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($users as $user)
                <tr>
                    <td class="px-5 py-4 font-medium text-ink-900">{{ $user->name }}</td>
                    <td class="px-5 py-4 text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-4">
                        <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold capitalize text-gray-600">{{ str_replace('_', ' ', $user->role) }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $user->status === 'active' ? 'bg-forest-100 text-forest-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">${{ number_format($user->wallet_balance, 2) }}</td>
                    <td class="px-5 py-4 text-gray-500">{{ $user->created_at->format('M j, Y') }}</td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-sm font-semibold text-brand-600">Manage</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-5 py-8 text-center text-gray-500">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $users->links() }}</div>
@endsection
