@extends('admin.layout')

@section('title', 'Pricing Plans — Admin')

@section('content')
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-ink-900">Pricing Plans</h1>
        <p class="mt-1 text-sm text-gray-600">Manage the plans shown on the public pricing page.</p>
    </div>
    <a href="{{ route('admin.plans.create') }}" class="btn-primary">Add a Plan</a>
</div>

<div class="mt-6 overflow-x-auto rounded-xl border border-gray-100 bg-white">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
            <tr>
                <th class="px-5 py-3">Plan</th>
                <th class="px-5 py-3">Price</th>
                <th class="px-5 py-3">Features</th>
                <th class="px-5 py-3">Visibility</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($plans as $plan)
                <tr>
                    <td class="px-5 py-4">
                        <p class="font-semibold text-ink-900">{{ $plan->name }}</p>
                        <p class="text-xs text-gray-500">{{ $plan->slug }} · order {{ $plan->sort_order }}</p>
                    </td>
                    <td class="px-5 py-4">{{ $plan->is_custom ? 'Custom' : '$'.number_format($plan->price, 2).' / '.$plan->billing_period }}</td>
                    <td class="px-5 py-4">{{ count($plan->features) }} features</td>
                    <td class="px-5 py-4">
                        {{ $plan->is_featured ? 'Featured' : 'Standard' }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="font-semibold text-brand-600">Edit</a>
                            <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Remove this pricing plan from the public page?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-semibold text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-5 py-8 text-center text-gray-500">No pricing plans yet. Add one to show it on the public pricing page.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $plans->links() }}</div>
@endsection
