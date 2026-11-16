<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => SiteSetting::values(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'support_email' => ['required', 'email', 'max:255'],
            'proxy_min_price' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:99999'],
            'proxy_max_price' => ['required', 'numeric', 'decimal:0,2', 'gt:proxy_min_price', 'max:100000'],
            'number_min_price' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:99999'],
            'number_max_price' => ['required', 'numeric', 'decimal:0,2', 'gt:number_min_price', 'max:100000'],
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated as $key => $value) {
                SiteSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => (string) $value],
                );
            }
        });

        return back()->with('status', 'Site settings and product pricing updated.');
    }
}
