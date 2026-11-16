<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $siteName = \App\Models\SiteSetting::query()->whereKey('site_name')->value('value') ?: 'NEXER PROX';
    $supportEmail = \App\Models\SiteSetting::query()->whereKey('support_email')->value('value') ?: 'support@nexerprox.com';
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $siteName }} provides premium residential, mobile and datacenter proxies plus virtual phone numbers across 150+ countries.">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/nexer-prox-mark.svg') }}">
    <title>{{ str_replace('NEXER PROX', $siteName, trim($__env->yieldContent('title', $siteName.' — Global Proxies & Virtual Numbers'))) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="marketing-shell font-sans text-ink-900">
    @include('partials.nav')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
