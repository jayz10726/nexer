<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'logged_in_at',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'logged_in_at' => 'datetime',
        ];
    }
}
