@extends('admin.layout')

@section('title', 'Virtual Numbers — Admin')

@section('content')
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-ink-900">All Virtual Numbers</h1>
    <a href="{{ route('admin.numbers.create') }}" class="btn-primary">Assign Number</a>
</div>

<div class="mt-6 overflow-x-auto rounded-xl border border-gray-100 bg-white">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
            <tr>
                <th class="px-5 py-3">User</th>
                <th class="px-5 py-3">Number</th>
                <th class="px-5 py-3">Country</th>
                <th class="px-5 py-3">Capability</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($numbers as $number)
                <tr>
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.users.show', $number->user) }}" class="font-medium text-brand-600">{{ $number->user->name ?? '—' }}</a>
                    </td>
                    <td class="px-5 py-4 font-mono text-xs">{{ $number->phone_number }}</td>
                    <td class="px-5 py-4">{{ $number->country }}</td>
                    <td class="px-5 py-4 uppercase">{{ $number->capability }}</td>
                    <td class="px-5 py-4">
                        <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $number->status === 'active' ? 'bg-forest-100 text-forest-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($number->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.numbers.edit', $number) }}" class="text-sm font-semibold text-brand-600">Edit</a>
                            <form method="POST" action="{{ route('admin.numbers.destroy', $number) }}" onsubmit="return confirm('Remove this number?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-semibold text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-500">No virtual numbers assigned yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $numbers->links() }}</div>
@endsection
