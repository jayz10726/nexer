<?php

namespace App\Support;

use App\Models\SiteSetting;

class PricingCatalog
{
    /**
     * Deterministically derive a $6–$10 price from a seed string, so the
     * same continent/country/type (or /service) combination always shows
     * the same price to every user, without needing a giant priced catalog
     * table. Swap this out for real supplier pricing when you have it.
     */
    public static function price(string $seed, float $min = 6, float $max = 10): float
    {
        $hash = crc32(strtolower($seed));
        $fraction = ($hash % 1000) / 1000;

        return round($min + $fraction * ($max - $min), 2);
    }

    public static function range(string $product): array
    {
        $settings = SiteSetting::values();

        return [
            (float) $settings[$product.'_min_price'],
            (float) $settings[$product.'_max_price'],
        ];
    }

    public static function proxyTypes(): array
    {
        return [
            'residential' => 'Residential',
            'datacenter' => 'Datacenter',
            'mobile' => 'Mobile',
            'isp' => 'ISP',
        ];
    }
}
