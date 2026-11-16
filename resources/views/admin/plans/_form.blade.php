@php
    $currentPlan = $plan ?? null;
    $isCustom = (bool) old('is_custom', $currentPlan?->is_custom ?? false);
    $features = old('features', implode("\n", $currentPlan?->features ?? []));
@endphp

<form method="POST" action="{{ $currentPlan ? route('admin.plans.update', $currentPlan) : route('admin.plans.store') }}" class="mt-6 max-w-3xl space-y-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm" x-data="{ isCustom: @js($isCustom) }">
    @csrf
    @if ($currentPlan)
        @method('PATCH')
    @endif

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <label class="block text-sm font-medium text-gray-700">
            Plan name
            <input name="name" value="{{ old('name', $currentPlan?->name) }}" required maxlength="120" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </label>
        <label class="block text-sm font-medium text-gray-700">
            URL slug
            <input name="slug" value="{{ old('slug', $currentPlan?->slug) }}" required maxlength="120" pattern="[A-Za-z0-9_-]+" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </label>
        <label class="block text-sm font-medium text-gray-700">
            Price (USD)
            <input name="price" type="number" step="0.01" min="0" value="{{ old('price', $currentPlan?->price) }}" :disabled="isCustom" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 disabled:bg-gray-100">
        </label>
        <label class="block text-sm font-medium text-gray-700">
            Billing period
            <input name="billing_period" value="{{ old('billing_period', $currentPlan?->billing_period ?? 'month') }}" required maxlength="40" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </label>
        <label class="block text-sm font-medium text-gray-700">
            Call-to-action label
            <input name="cta_label" value="{{ old('cta_label', $currentPlan?->cta_label ?? 'Choose Plan') }}" required maxlength="80" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </label>
        <label class="block text-sm font-medium text-gray-700">
            Display order
            <input name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', $currentPlan?->sort_order ?? 0) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </label>
    </div>

    <div class="flex flex-wrap gap-6">
        <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
            <input type="hidden" name="is_custom" value="0">
            <input type="checkbox" name="is_custom" value="1" x-model="isCustom" @checked($isCustom) class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
            Custom pricing
        </label>
        <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
            <input type="hidden" name="is_featured" value="0">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $currentPlan?->is_featured ?? false)) class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
            Highlight as featured
        </label>
    </div>

    <label class="block text-sm font-medium text-gray-700">
        Features (one per line)
        <textarea name="features" rows="7" required maxlength="4000" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">{{ $features }}</textarea>
    </label>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn-primary">{{ $currentPlan ? 'Save Plan' : 'Create Plan' }}</button>
        <a href="{{ route('admin.plans') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
