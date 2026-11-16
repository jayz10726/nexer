<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('virtual_numbers', function (Blueprint $table) {
            $table->string('continent')->nullable()->after('user_id');
            $table->string('service')->nullable()->after('capability');
            $table->decimal('price', 8, 2)->nullable()->after('service');
        });
    }

    public function down(): void
    {
        Schema::table('virtual_numbers', function (Blueprint $table) {
            $table->dropColumn(['continent', 'service', 'price']);
        });
    }
};
