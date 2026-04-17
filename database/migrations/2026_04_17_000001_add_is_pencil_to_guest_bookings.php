<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_bookings', function (Blueprint $table) {
            $table->boolean('is_pencil')->default(false)->after('has_conflict');
        });
    }

    public function down(): void
    {
        Schema::table('guest_bookings', function (Blueprint $table) {
            $table->dropColumn('is_pencil');
        });
    }
};