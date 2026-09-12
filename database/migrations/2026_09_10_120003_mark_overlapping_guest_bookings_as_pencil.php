<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A previous migration may be marked as run without this column
        // existing in older local databases.
        if (!Schema::hasColumn('guest_bookings', 'conflict_with_id')) {
            Schema::table('guest_bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('conflict_with_id')->nullable()->after('has_conflict');
            });
        }

        // A venue is only a display label; one 4-hour event blocks every venue.
        DB::statement(<<<'SQL'
            UPDATE guest_bookings AS booking
            INNER JOIN guest_bookings AS other
                ON booking.id <> other.id
                AND booking.event_date = other.event_date
                AND booking.event_time_start < ADDTIME(other.event_time_start, '04:00:00')
                AND ADDTIME(booking.event_time_start, '04:00:00') > other.event_time_start
            SET booking.status = 'pencil',
                booking.is_pencil = 1,
                booking.has_conflict = 1,
                booking.conflict_with_id = other.id
            WHERE booking.status IN ('pending', 'pencil')
              AND other.status IN ('pending', 'pencil')
        SQL);
    }

    public function down(): void
    {
        // Keep recorded Pencil decisions when rolling back this data migration.
    }
};
