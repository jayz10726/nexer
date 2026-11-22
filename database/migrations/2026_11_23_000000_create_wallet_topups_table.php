<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_topups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->decimal('wallet_amount', 12, 2);
            $table->string('currency', 3);
            $table->unsignedBigInteger('gateway_amount');
            $table->decimal('usd_to_paystack_rate', 12, 6);
            $table->string('status')->default('pending')->index();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('gateway_reference')->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropUnique(['gateway_reference']);
            $table->dropColumn('gateway_reference');
        });

        Schema::dropIfExists('wallet_topups');
    }
};
