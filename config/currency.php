<?php

return [
    'wallet' => [
        'code' => 'USD',
        'symbol' => '$',
    ],
    'paystack' => [
        'code' => env('PAYSTACK_CURRENCY', 'KES'),
        'usd_rate' => (float) env('USD_TO_KES', 130),
    ],
];