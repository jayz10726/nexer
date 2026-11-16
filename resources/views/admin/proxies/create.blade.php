@extends('admin.layout')

@section('title', 'Assign Proxy — Admin')

@section('content')
<h1 class="text-2xl font-bold text-ink-900">Assign a Proxy</h1>

<div class="mt-6 max-w-2xl card">
    <form method="POST" action="{{ route('admin.proxies.store') }}">
        @include('admin.proxies._form')
    </form>
</div>
@endsection
