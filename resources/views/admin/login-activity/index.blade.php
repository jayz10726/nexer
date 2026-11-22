@extends('admin.layout')

@section('title', 'Login Activity — Admin')

@section('content')
<h1 class="text-2xl font-bold text-ink-900">Login Activity</h1>
<p class="mt-2 text-sm text-gray-500">Review successful sign-ins and currently active sessions.</p>

<section class="mt-8">
    <h2 class="text-lg font-semibold text-ink-900">Currently Active Sessions</h2>
    @if (! $activeSessionTrackingAvailable)
        <p class="mt-3 rounded-lg bg-amber-50 p-4 text-sm text-amber-800">
            Active session visibility requires <code>SESSION_DRIVER=database</code>.
        </p>
    @else
        <div class="mt-4 overflow-x-auto rounded-xl border border-gray-100 bg-white">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">User</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Last Activity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($activeSessions as $activeSession)
                        <tr>
                            <td class="px-5 py-4 font-medium text-ink-900">{{ $activeSession->name }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $activeSession->email }}</td>
                            <td class="px-5 py-4 text-gray-500">
                                {{ \Illuminate\Support\Carbon::createFromTimestamp($activeSession->last_activity)->format('M j, Y · g:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-gray-500">No active user sessions.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $activeSessions->links() }}</div>
    @endif
</section>

<section class="mt-10">
    <h2 class="text-lg font-semibold text-ink-900">Successful Sign-ins</h2>
    <div class="mt-4 overflow-x-auto rounded-xl border border-gray-100 bg-white">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-3">User</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Signed In</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($loginActivities as $activity)
                    <tr>
                        <td class="px-5 py-4 font-medium text-ink-900">{{ $activity->name }}</td>
                        <td class="px-5 py-4 text-gray-600">{{ $activity->email }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $activity->logged_in_at->format('M j, Y · g:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-5 py-8 text-center text-gray-500">No successful sign-ins recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $loginActivities->links() }}</div>
</section>
@endsection
