<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualNumber extends Model
{
    protected $fillable = [
        'user_id',
        'continent',
        'phone_number',
        'country',
        'capability',
        'service',
        'price',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
