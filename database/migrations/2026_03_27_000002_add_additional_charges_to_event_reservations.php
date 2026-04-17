<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_reservations', function (Blueprint $table) {
            $table->json('additional_charges')->nullable()->after('special_requests');
            $table->decimal('additional_total', 10, 2)->default(0)->after('additional_charges');
        });
    }

    public function down(): void
    {
        Schema::table('event_reservations', function (Blueprint $table) {
            $table->dropColumn(['additional_charges', 'additional_total']);
        });
    }
};