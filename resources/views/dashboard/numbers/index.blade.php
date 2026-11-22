@extends('dashboard.layout')

@section('title', 'Virtual Numbers — NEXER PROX')

@section('content')
<div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h1 class="text-2xl font-bold text-ink-900">Virtual Numbers</h1>
    <a href="{{ route('dashboard.numbers.buy') }}" class="btn-primary shrink-0">Get a Number</a>
</div>

<section class="inventory-section mt-6">
    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Browse numbers by continent</h2>
    <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
        @foreach ($continents as $continent => $countries)
            <a href="{{ route('dashboard.numbers.buy', ['continent' => $continent]) }}"
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
                <th class="px-5 py-3">Number</th>
                <th class="px-5 py-3">Country</th>
                <th class="px-5 py-3">Service</th>
                <th class="px-5 py-3">Price</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Expires</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($numbers as $number)
                <tr>
                    <td class="px-5 py-4 font-mono text-xs">{{ $number->phone_number }}</td>
                    <td class="px-5 py-4">{{ $number->country }}@if($number->continent)<span class="text-gray-400"> · {{ $number->continent }}</span>@endif</td>
                    <td class="px-5 py-4">{{ $number->service ? \App\Support\ServiceCatalog::label($number->service) : '—' }}</td>
                    <td class="px-5 py-4">{{ $number->price ? '$'.number_format($number->price, 2) : '—' }}</td>
                    <td class="px-5 py-4">
                        <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $number->status === 'active' ? 'bg-forest-100 text-forest-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($number->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">{{ $number->expires_at?->format('M j, Y') ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-500">You don't have any virtual numbers yet. <a href="{{ route('dashboard.numbers.buy') }}" class="font-semibold text-brand-600">Get your first one</a>.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $numbers->links() }}</div>
@endsection
