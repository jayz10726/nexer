@extends('admin.layout')

@section('title', 'Site Settings — Admin')

@section('content')
<div>
    <h1 class="text-2xl font-bold text-ink-900">Site Settings</h1>
    <p class="mt-1 text-sm text-gray-600">Update public contact details and the prices used by the product purchase flows.</p>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" class="mt-6 max-w-3xl space-y-8">
    @csrf
    @method('PATCH')

    <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-ink-900">Brand and support</h2>
        <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
            <label class="block text-sm font-medium text-gray-700">
                Site name
                <input name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required maxlength="120" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </label>
            <label class="block text-sm font-medium text-gray-700">
                Support email
                <input name="support_email" type="email" value="{{ old('support_email', $settings['support_email']) }}" required maxlength="255" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </label>
        </div>
    </section>

    <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-ink-900">Pay-as-you-go prices</h2>
        <p class="mt-1 text-sm text-gray-500">Each proxy or number uses a consistent price based on its selection, within the range below.</p>
        <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
            <label class="block text-sm font-medium text-gray-700">
                Proxy minimum (USD)
                <input name="proxy_min_price" type="number" step="0.01" min="0.01" value="{{ old('proxy_min_price', $settings['proxy_min_price']) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </label>
            <label class="block text-sm font-medium text-gray-700">
                Proxy maximum (USD)
                <input name="proxy_max_price" type="number" step="0.01" min="0.02" value="{{ old('proxy_max_price', $settings['proxy_max_price']) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </label>
            <label class="block text-sm font-medium text-gray-700">
                Virtual number minimum (USD)
                <input name="number_min_price" type="number" step="0.01" min="0.01" value="{{ old('number_min_price', $settings['number_min_price']) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </label>
            <label class="block text-sm font-medium text-gray-700">
                Virtual number maximum (USD)
                <input name="number_max_price" type="number" step="0.01" min="0.02" value="{{ old('number_max_price', $settings['number_max_price']) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </label>
        </div>
    </section>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn-primary">Save Site Settings</button>
        <a href="{{ route('admin.plans') }}" class="btn-secondary">Manage Pricing Plans</a>
    </div>
</form>
@endsection
