<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proxy;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VirtualNumber;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'userCount' => User::where('role', 'user')->count(),
            'adminCount' => User::whereIn('role', ['admin', 'super_admin'])->count(),
            'proxyCount' => Proxy::count(),
            'numberCount' => VirtualNumber::count(),
            'totalWalletBalance' => User::sum('wallet_balance'),
            'recentUsers' => User::latest()->take(5)->get(),
            'recentTransactions' => Transaction::with('user')->latest()->take(8)->get(),
        ]);
    }
}
