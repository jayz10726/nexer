@extends('admin.layout')

@section('title', 'Assign Virtual Number — Admin')

@section('content')
<h1 class="text-2xl font-bold text-ink-900">Assign a Virtual Number</h1>

<div class="mt-6 max-w-2xl card">
    <form method="POST" action="{{ route('admin.numbers.store') }}">
        @include('admin.numbers._form')
    </form>
</div>
@endsection
