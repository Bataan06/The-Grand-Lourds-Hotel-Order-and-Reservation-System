<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make sure conflict_with_id exists.
        if (!Schema::hasColumn('guest_bookings', 'conflict_with_id')) {
            Schema::table('guest_bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('conflict_with_id')
                    ->nullable()
                    ->after('has_conflict');
            });
        }

        // Get all pending and pencil bookings.
        $bookings = DB::table('guest_bookings')
            ->whereIn('status', ['pending', 'pencil'])
            ->orderBy('id')
            ->get([
                'id',
                'event_date',
                'event_time_start',
            ]);

        foreach ($bookings as $booking) {
            if (!$booking->event_date || !$booking->event_time_start) {
                continue;
            }

            $bookingStart = Carbon::parse(
                $booking->event_date . ' ' . $booking->event_time_start
            );

            $bookingEnd = $bookingStart->copy()->addHours(4);

            foreach ($bookings as $other) {
                if ($booking->id === $other->id) {
                    continue;
                }

                if ($booking->event_date != $other->event_date) {
                    continue;
                }

                if (!$other->event_time_start) {
                    continue;
                }

                $otherStart = Carbon::parse(
                    $other->event_date . ' ' . $other->event_time_start
                );

                $otherEnd = $otherStart->copy()->addHours(4);

                // Check if the two 4-hour schedules overlap.
                if (
                    $bookingStart->lt($otherEnd) &&
                    $bookingEnd->gt($otherStart)
                ) {
                    DB::table('guest_bookings')
                        ->where('id', $booking->id)
                        ->update([
                            'status' => 'pencil',
                            'is_pencil' => true,
                            'has_conflict' => true,
                            'conflict_with_id' => $other->id,
                        ]);

                    break;
                }
            }
        }
    }

    public function down(): void
    {
        // Keep recorded Pencil decisions when rolling back this data migration.
    }
};