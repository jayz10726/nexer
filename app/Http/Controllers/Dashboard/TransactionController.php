<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $transactions = $request->user()->transactions()->latest()->paginate(15);

        return view('dashboard.transactions.index', compact('transactions'));
    }
}
