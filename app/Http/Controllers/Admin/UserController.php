<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->role, fn ($query, $role) => $query->where('role', $role))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['proxies', 'virtualNumbers']);
        $transactions = $user->transactions()->latest()->take(20)->get();

        return view('admin.users.show', compact('user', 'transactions'));
    }

    /**
     * Update a user's role and account status. Only a super admin can grant
     * or revoke admin/super_admin roles; regular admins can only manage
     * plain "user" accounts' status.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['user', 'admin', 'super_admin'])],
            'status' => ['required', Rule::in(['active', 'suspended'])],
        ]);

        if (! $request->user()->isSuperAdmin() && $validated['role'] !== $user->role) {
            abort(403, 'Only the super admin can change user roles.');
        }

        if ($user->id === $request->user()->id && $validated['role'] !== 'super_admin') {
            abort(403, 'You cannot remove your own super admin role.');
        }

        $user->update($validated);

        return back()->with('status', 'User updated successfully.');
    }

    public function adjustWallet(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:-100000', 'max:100000', 'not_in:0'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $user->increment('wallet_balance', $validated['amount']);

        Transaction::create([
            'user_id' => $user->id,
            'type' => $validated['amount'] > 0 ? 'admin_credit' : 'admin_debit',
            'amount' => abs($validated['amount']),
            'balance_after' => $user->wallet_balance,
            'description' => $validated['note'] ?: 'Manual wallet adjustment by admin',
            'status' => 'completed',
        ]);

        return back()->with('status', 'Wallet balance adjusted.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            abort(403, 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('status', 'User deleted.');
    }
}
