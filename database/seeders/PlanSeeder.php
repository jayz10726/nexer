<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price' => 39,
                'billing_period' => 'month',
                'is_custom' => false,
                'features' => [
                    '40+ proxy locations',
                    '5 virtual numbers',
                    '80 Mbps throughput',
                    'Email support',
                ],
                'cta_label' => 'Choose Starter',
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Growth',
                'slug' => 'growth',
                'price' => 119,
                'billing_period' => 'month',
                'is_custom' => false,
                'features' => [
                    '140+ countries covered',
                    '40 virtual numbers',
                    'Unlimited bandwidth',
                    'Priority 24/7 support',
                ],
                'cta_label' => 'Choose Growth',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => null,
                'billing_period' => 'custom',
                'is_custom' => true,
                'features' => [
                    'Dedicated IP pools',
                    'Custom routing rules',
                    '99.99% SLA',
                    'Full API access',
                ],
                'cta_label' => 'Contact Sales',
                'is_featured' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
