<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_bookings', function (Blueprint $table) {
            $table->enum('status', ['pending', 'pencil', 'confirmed', 'completed', 'cancelled'])
                ->default('pending')
                ->change();
        });
    }

    public function down(): void
    {
        // An ENUM cannot be narrowed while rows still contain "pencil".
        DB::table('guest_bookings')->where('status', 'pencil')->update(['status' => 'pending']);

        Schema::table('guest_bookings', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])
                ->default('pending')
                ->change();
        });
    }
};
