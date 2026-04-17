<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_reservations', function (Blueprint $table) {
            $table->decimal('downpayment', 10, 2)->nullable()->after('total_amount');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid')->after('downpayment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_reservations', function (Blueprint $table) {
            $table->dropColumn(['downpayment', 'payment_status']);
        });
    }
};