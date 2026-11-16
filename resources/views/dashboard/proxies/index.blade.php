@extends('dashboard.layout')

@section('title', 'My Proxies — NEXER PROX')

@section('content')
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-ink-900">My Proxies</h1>
    <a href="{{ route('dashboard.proxies.buy') }}" class="btn-primary">Buy a Proxy</a>
</div>

<section class="mt-6">
    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Browse proxies by continent</h2>
    <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
        @foreach ($continents as $continent => $countries)
            <a href="{{ route('dashboard.proxies.buy', ['continent' => $continent]) }}"
               data-continent="{{ \Illuminate\Support\Str::slug($continent) }}"
               style="--card-index: {{ $loop->index }}"
               class="continent-card rounded-2xl px-4 py-5 text-sm font-bold">
                <span class="relative z-10 block">{{ $continent }}</span>
                <span class="relative z-10 mt-1 block text-xs font-medium opacity-70">{{ count($countries) }} countries</span>
            </a>
        @endforeach
    </div>
</section>

<div class="inventory-section mt-6 overflow-x-auto rounded-xl border border-gray-100 bg-white">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
            <tr>
                <th class="px-5 py-3">Type</th>
                <th class="px-5 py-3">IP : Port</th>
                <th class="px-5 py-3">Country</th>
                <th class="px-5 py-3">Price</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Expires</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($proxies as $proxy)
                <tr>
                    <td class="px-5 py-4 capitalize">{{ $proxy->type }}</td>
                    <td class="px-5 py-4 font-mono text-xs">{{ $proxy->ip_address }}:{{ $proxy->port }}</td>
                    <td class="px-5 py-4">{{ $proxy->country }}@if($proxy->continent)<span class="text-gray-400"> · {{ $proxy->continent }}</span>@endif</td>
                    <td class="px-5 py-4">{{ $proxy->price ? '$'.number_format($proxy->price, 2) : '—' }}</td>
                    <td class="px-5 py-4">
                        <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $proxy->status === 'active' ? 'bg-forest-100 text-forest-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($proxy->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">{{ $proxy->expires_at?->format('M j, Y') ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-500">You don't have any proxies yet. <a href="{{ route('dashboard.proxies.buy') }}" class="font-semibold text-brand-600">Buy your first one</a>.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $proxies->links() }}</div>
@endsection
