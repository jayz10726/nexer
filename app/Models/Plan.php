<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'billing_period',
        'is_custom',
        'features',
        'cta_label',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_custom' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
    ];
}
