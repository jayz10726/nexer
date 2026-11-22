@extends('admin.layout')

@section('title', 'Proxies — Admin')

@section('content')
<div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h1 class="text-2xl font-bold text-ink-900">All Proxies</h1>
    <a href="{{ route('admin.proxies.create') }}" class="btn-primary shrink-0">Assign Proxy</a>
</div>

<div class="mt-6 overflow-x-auto rounded-xl border border-gray-100 bg-white">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
            <tr>
                <th class="px-5 py-3">User</th>
                <th class="px-5 py-3">Type</th>
                <th class="px-5 py-3">IP : Port</th>
                <th class="px-5 py-3">Country</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($proxies as $proxy)
                <tr>
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.users.show', $proxy->user) }}" class="font-medium text-brand-600">{{ $proxy->user->name ?? '—' }}</a>
                    </td>
                    <td class="px-5 py-4 capitalize">{{ $proxy->type }}</td>
                    <td class="px-5 py-4 font-mono text-xs">{{ $proxy->ip_address }}:{{ $proxy->port }}</td>
                    <td class="px-5 py-4">{{ $proxy->country }}</td>
                    <td class="px-5 py-4">
                        <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $proxy->status === 'active' ? 'bg-forest-100 text-forest-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($proxy->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.proxies.edit', $proxy) }}" class="text-sm font-semibold text-brand-600">Edit</a>
                            <form method="POST" action="{{ route('admin.proxies.destroy', $proxy) }}" onsubmit="return confirm('Remove this proxy?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-semibold text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-500">No proxies assigned yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $proxies->links() }}</div>
@endsection
