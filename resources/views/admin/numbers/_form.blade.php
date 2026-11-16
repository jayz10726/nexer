@csrf
@if (isset($number)) @method('PATCH') @endif

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Assign to User</label>
        <select name="user_id" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            <option value="">Select a user...</option>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" @selected(old('user_id', $number->user_id ?? null) == $u->id)>{{ $u->name }} ({{ $u->email }})</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
        <input type="text" name="phone_number" required value="{{ old('phone_number', $number->phone_number ?? '') }}"
            placeholder="+1 555 123 4567"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Country</label>
        <input type="text" name="country" required value="{{ old('country', $number->country ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Capability</label>
        <select name="capability" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            @foreach (['sms', 'voice', 'both'] as $cap)
                <option value="{{ $cap }}" @selected(old('capability', $number->capability ?? null) === $cap)>{{ strtoupper($cap) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            <option value="active" @selected(old('status', $number->status ?? 'active') === 'active')>Active</option>
            <option value="expired" @selected(old('status', $number->status ?? null) === 'expired')>Expired</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Expires At</label>
        <input type="date" name="expires_at" value="{{ old('expires_at', isset($number) && $number->expires_at ? $number->expires_at->format('Y-m-d') : '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
</div>

<button type="submit" class="btn-primary mt-6">{{ isset($number) ? 'Save Changes' : 'Assign Number' }}</button>
