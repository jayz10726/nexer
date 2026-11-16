<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(): View
    {
        $plans = Plan::orderBy('sort_order')->paginate(15);

        return view('admin.plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('admin.plans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Plan::create($this->validatedPlan($request));

        return redirect()->route('admin.plans')->with('status', 'Plan created.');
    }

    public function edit(Plan $plan): View
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $plan->update($this->validatedPlan($request, $plan));

        return redirect()->route('admin.plans')->with('status', 'Plan updated.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $plan->delete();

        return back()->with('status', 'Plan removed.');
    }

    private function validatedPlan(Request $request, ?Plan $plan = null): array
    {
        $request->merge([
            'features' => trim((string) $request->input('features')),
        ]);
        $slugRule = Rule::unique('plans', 'slug');

        if ($plan) {
            $slugRule->ignore($plan->id);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => [
                'required',
                'alpha_dash',
                'max:120',
                $slugRule,
            ],
            'price' => ['nullable', 'numeric', 'decimal:0,2', 'min:0', 'max:99999999', 'required_if:is_custom,0'],
            'billing_period' => ['required', 'string', 'max:40'],
            'is_custom' => ['required', 'boolean'],
            'features' => ['required', 'string', 'max:4000'],
            'cta_label' => ['required', 'string', 'max:80'],
            'is_featured' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);

        $validated['features'] = array_values(array_filter(
            array_map('trim', preg_split('/\R/u', $validated['features']) ?: []),
            fn ($feature) => $feature !== '',
        ));

        if ($validated['is_custom']) {
            $validated['price'] = null;
        }

        return $validated;
    }
}
