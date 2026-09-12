<?php

use App\Models\GuestBooking;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Repair records confirmed by the old Admin flow.
     * A reservation with partial/full payment owns the slot.
     * Any overlapping unpaid confirmed booking is returned to Pencil.
     */
    public function up(): void
    {
        $securedBookings = GuestBooking::query()
            ->where('status', 'confirmed')
            ->whereIn('payment_status', ['partial', 'paid'])
            ->whereNotNull('event_time_start')
            ->get();

        foreach ($securedBookings as $securedBooking) {

            $eventDate = Carbon::parse(
                $securedBooking->event_date
            )->format('Y-m-d');

            $securedStart = Carbon::parse(
                $eventDate . ' ' . $securedBooking->event_time_start
            );

            $securedEnd = $securedStart->copy()->addHours(4);

            $conflictingBookings = GuestBooking::query()
                ->whereDate('event_date', $eventDate)
                ->where('id', '!=', $securedBooking->id)
                ->where('status', 'confirmed')
                ->where('payment_status', 'unpaid')
                ->whereNotNull('event_time_start')
                ->get();

            foreach ($conflictingBookings as $booking) {

                $bookingStart = Carbon::parse(
                    $eventDate . ' ' . $booking->event_time_start
                );

                $bookingEnd = $bookingStart->copy()->addHours(4);

                if (
                    $bookingStart->lt($securedEnd) &&
                    $bookingEnd->gt($securedStart)
                ) {
                    $booking->update([
                        'status'           => 'pencil',
                        'is_pencil'        => true,
                        'has_conflict'     => true,
                        'conflict_with_id' => $securedBooking->id,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // The paid reservation remains the valid holder of the slot.
    }
};