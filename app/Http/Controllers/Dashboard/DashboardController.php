<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('dashboard.index', [
            'proxyCount' => $user->proxies()->count(),
            'numberCount' => $user->virtualNumbers()->count(),
            'walletBalance' => $user->wallet_balance,
            'recentTransactions' => $user->transactions()->latest()->take(5)->get(),
        ]);
    }
}
