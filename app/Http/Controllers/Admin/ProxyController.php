<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proxy;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProxyController extends Controller
{
    public function index(): View
    {
        $proxies = Proxy::with('user')->latest()->paginate(20);

        return view('admin.proxies.index', compact('proxies'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();

        return view('admin.proxies.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'in:residential,mobile,datacenter'],
            'ip_address' => ['required', 'ip'],
            'port' => ['required', 'integer', 'min:1', 'max:65535'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,expired'],
            'expires_at' => ['nullable', 'date'],
        ]);

        Proxy::create($validated);

        return redirect()->route('admin.proxies')->with('status', 'Proxy assigned successfully.');
    }

    public function edit(Proxy $proxy): View
    {
        $users = User::orderBy('name')->get();

        return view('admin.proxies.edit', compact('proxy', 'users'));
    }

    public function update(Request $request, Proxy $proxy): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'in:residential,mobile,datacenter'],
            'ip_address' => ['required', 'ip'],
            'port' => ['required', 'integer', 'min:1', 'max:65535'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,expired'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $proxy->update($validated);

        return redirect()->route('admin.proxies')->with('status', 'Proxy updated successfully.');
    }

    public function destroy(Proxy $proxy): RedirectResponse
    {
        $proxy->delete();

        return back()->with('status', 'Proxy removed.');
    }
}
