<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VirtualNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VirtualNumberController extends Controller
{
    public function index(): View
    {
        $numbers = VirtualNumber::with('user')->latest()->paginate(20);

        return view('admin.numbers.index', compact('numbers'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();

        return view('admin.numbers.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'phone_number' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:255'],
            'capability' => ['required', 'in:sms,voice,both'],
            'status' => ['required', 'in:active,expired'],
            'expires_at' => ['nullable', 'date'],
        ]);

        VirtualNumber::create($validated);

        return redirect()->route('admin.numbers')->with('status', 'Virtual number assigned successfully.');
    }

    public function edit(VirtualNumber $number): View
    {
        $users = User::orderBy('name')->get();

        return view('admin.numbers.edit', compact('number', 'users'));
    }

    public function update(Request $request, VirtualNumber $number): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'phone_number' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:255'],
            'capability' => ['required', 'in:sms,voice,both'],
            'status' => ['required', 'in:active,expired'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $number->update($validated);

        return redirect()->route('admin.numbers')->with('status', 'Virtual number updated successfully.');
    }

    public function destroy(VirtualNumber $number): RedirectResponse
    {
        $number->delete();

        return back()->with('status', 'Virtual number removed.');
    }
}
