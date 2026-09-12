<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('guest_bookings')
            ->where('status', 'pending')
            ->where('has_conflict', true)
            ->update([
                'status' => 'pencil',
                'is_pencil' => true,
            ]);
    }

    public function down(): void
    {
        DB::table('guest_bookings')
            ->where('status', 'pencil')
            ->where('has_conflict', true)
            ->update([
                'status' => 'pending',
                'is_pencil' => false,
            ]);
    }
};
