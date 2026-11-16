@extends('admin.layout')

@section('title', 'Add Pricing Plan — Admin')

@section('content')
<h1 class="text-2xl font-bold text-ink-900">Add a Pricing Plan</h1>
<p class="mt-1 text-sm text-gray-600">New plans are shown on the public pricing page after saving.</p>
@include('admin.plans._form')
@endsection
