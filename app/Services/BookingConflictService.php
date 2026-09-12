<?php

namespace App\Services;

use App\Models\GuestBooking;
use Carbon\Carbon;

class BookingConflictService
{
    /**
     * Secure a booking's four-hour time slot and move every still-unconfirmed
     * overlapping booking to Pencil status.
     */
    public function confirm(GuestBooking $booking): void
    {
        $booking->update([
            'status'           => 'confirmed',
            'is_pencil'        => false,
            'has_conflict'     => false,
            'conflict_with_id' => null,
        ]);

        if (!$booking->event_time_start) {
            return;
        }

        $start = Carbon::parse($booking->event_time_start);
        $end = $start->copy()->addHours(4);

        GuestBooking::whereDate('event_date', $booking->event_date)
            ->where('id', '!=', $booking->id)
            ->whereIn('status', ['pending', 'pencil'])
            ->whereRaw('event_time_start < ?', [$end->format('H:i:s')])
            ->whereRaw("ADDTIME(event_time_start, '04:00:00') > ?", [$start->format('H:i:s')])
            ->update([
                'status'           => 'pencil',
                'is_pencil'        => true,
                'has_conflict'     => true,
                'conflict_with_id' => $booking->id,
            ]);
    }
}
