<?php

use App\Models\GuestBooking;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Repair records confirmed by the old Admin flow. A reservation with a
     * verified partial/full payment owns the slot; an overlapping unpaid one
     * is returned to Pencil for staff review.
     */
    public function up(): void
    {
        $securedBookings = GuestBooking::query()
            ->where('status', 'confirmed')
            ->whereIn('payment_status', ['partial', 'paid'])
            ->whereNotNull('event_time_start')
            ->get();

        foreach ($securedBookings as $securedBooking) {
            $start = Carbon::parse($securedBooking->event_time_start);
            $end = $start->copy()->addHours(4);

            GuestBooking::query()
                ->whereDate('event_date', $securedBooking->event_date)
                ->where('id', '!=', $securedBooking->id)
                ->where('status', 'confirmed')
                ->where('payment_status', 'unpaid')
                ->whereRaw('event_time_start < ?', [$end->format('H:i:s')])
                ->whereRaw("ADDTIME(event_time_start, '04:00:00') > ?", [$start->format('H:i:s')])
                ->update([
                    'status'           => 'pencil',
                    'is_pencil'        => true,
                    'has_conflict'     => true,
                    'conflict_with_id' => $securedBooking->id,
                    'updated_at'       => now(),
                ]);
        }
    }

    public function down(): void
    {
        // The paid reservation is the valid holder of the conflicting slot.
    }
};
