<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value');
            $table->timestamps();
        });

        $now = now();
        $settings = [
            'site_name' => 'NEXER PROX',
            'support_email' => 'support@nexerprox.com',
            'proxy_min_price' => '6.00',
            'proxy_max_price' => '10.00',
            'number_min_price' => '6.00',
            'number_max_price' => '10.00',
        ];

        DB::table('site_settings')->insert(
            collect($settings)->map(fn ($value, $key) => [
                'key' => $key,
                'value' => $value,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all()
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
