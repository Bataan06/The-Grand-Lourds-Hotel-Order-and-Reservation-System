<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_bookings', function (Blueprint $table) {
            $table->boolean('has_conflict')->default(false)->after('payment_status');
            $table->timestamp('expires_at')->nullable()->after('has_conflict');
        });
    }

    public function down(): void
    {
        Schema::table('guest_bookings', function (Blueprint $table) {
            $table->dropColumn(['has_conflict', 'expires_at']);
        });
    }
};