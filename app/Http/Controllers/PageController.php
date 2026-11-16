<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('home');
    }

    public function proxies(): View
    {
        return view('proxies');
    }

    public function virtualNumbers(): View
    {
        return view('virtual-numbers');
    }

    public function pricing(): View
    {
        $plans = Plan::orderBy('sort_order')->get();

        return view('pricing', compact('plans'));
    }
}
