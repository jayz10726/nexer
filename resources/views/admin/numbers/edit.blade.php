@extends('admin.layout')

@section('title', 'Edit Virtual Number — Admin')

@section('content')
<h1 class="text-2xl font-bold text-ink-900">Edit Virtual Number</h1>

<div class="mt-6 max-w-2xl card">
    <form method="POST" action="{{ route('admin.numbers.update', $number) }}">
        @include('admin.numbers._form')
    </form>
</div>
@endsection
