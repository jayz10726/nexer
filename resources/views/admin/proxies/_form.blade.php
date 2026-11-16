@csrf
@if (isset($proxy)) @method('PATCH') @endif

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Assign to User</label>
        <select name="user_id" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            <option value="">Select a user...</option>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" @selected(old('user_id', $proxy->user_id ?? null) == $u->id)>{{ $u->name }} ({{ $u->email }})</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Type</label>
        <select name="type" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            @foreach (['residential', 'mobile', 'datacenter'] as $type)
                <option value="{{ $type }}" @selected(old('type', $proxy->type ?? null) === $type)>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Country</label>
        <input type="text" name="country" required value="{{ old('country', $proxy->country ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">IP Address</label>
        <input type="text" name="ip_address" required value="{{ old('ip_address', $proxy->ip_address ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Port</label>
        <input type="number" name="port" required value="{{ old('port', $proxy->port ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Username</label>
        <input type="text" name="username" required value="{{ old('username', $proxy->username ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input type="text" name="password" required value="{{ old('password', $proxy->password ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            <option value="active" @selected(old('status', $proxy->status ?? 'active') === 'active')>Active</option>
            <option value="expired" @selected(old('status', $proxy->status ?? null) === 'expired')>Expired</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Expires At</label>
        <input type="date" name="expires_at" value="{{ old('expires_at', isset($proxy) && $proxy->expires_at ? $proxy->expires_at->format('Y-m-d') : '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
</div>

<button type="submit" class="btn-primary mt-6">{{ isset($proxy) ? 'Save Changes' : 'Assign Proxy' }}</button>
