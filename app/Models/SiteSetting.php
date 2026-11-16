<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    public static function defaults(): array
    {
        return [
            'site_name' => 'NEXER PROX',
            'support_email' => 'support@nexerprox.com',
            'proxy_min_price' => '6.00',
            'proxy_max_price' => '10.00',
            'number_min_price' => '6.00',
            'number_max_price' => '10.00',
        ];
    }

    public static function values(): array
    {
        return array_replace(
            self::defaults(),
            self::query()->pluck('value', 'key')->all(),
        );
    }
}
