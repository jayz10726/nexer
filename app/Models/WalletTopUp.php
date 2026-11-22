<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTopUp extends Model
{
    protected $table = 'wallet_topups';

    protected $fillable = [
        'user_id',
        'reference',
        'wallet_amount',
        'currency',
        'gateway_amount',
        'usd_to_paystack_rate',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'wallet_amount' => 'decimal:2',
            'gateway_amount' => 'integer',
            'usd_to_paystack_rate' => 'decimal:6',
        ];
    }
}
