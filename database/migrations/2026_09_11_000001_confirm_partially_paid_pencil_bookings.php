<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Bring existing records in line with the rule that a partial/full payment
     * reserves the schedule. Future records are handled by the staff controller.
     */
    public function up(): void
    {
        DB::table('guest_bookings')
            ->where('status', 'pencil')
            ->whereIn('payment_status', ['partial', 'paid'])
            ->update([
                'status'           => 'confirmed',
                'is_pencil'        => false,
                'has_conflict'     => false,
                'conflict_with_id' => null,
                'updated_at'       => now(),
            ]);
    }

    public function down(): void
    {
        // Data is intentionally not reverted: a paid reservation must stay confirmed.
    }
};
