@extends('admin.layout')

@section('title', 'Edit Pricing Plan — Admin')

@section('content')
<h1 class="text-2xl font-bold text-ink-900">Edit {{ $plan->name }}</h1>
<p class="mt-1 text-sm text-gray-600">Changes appear on the public pricing page as soon as you save.</p>
@include('admin.plans._form')
@endsection
