<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_bookings', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('amount_paid');
            $table->string('payment_proof_status')->default('none')->after('payment_proof');
            // none, submitted, verified, rejected
        });
    }

    public function down(): void
    {
        Schema::table('guest_bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_proof', 'payment_proof_status']);
        });
    }
};