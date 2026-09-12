<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\GuestBooking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoCompleteFinishedBookings extends Command
{
    protected $signature = 'bookings:auto-complete';
    protected $description = 'Mark confirmed bookings as completed after their four-hour event window ends';

    public function handle(): int
    {
        $completed = 0;

        GuestBooking::where('status', 'confirmed')
            ->whereDate('event_date', '<=', today())
            ->cursor()
            ->each(function (GuestBooking $booking) use (&$completed) {
                if (!$booking->event_time_start) return;

                $eventEnd = Carbon::parse($booking->event_date->format('Y-m-d') . ' ' . $booking->event_time_start)->addHours(4);
                if ($eventEnd->isFuture()) return;

                $booking->update(['status' => 'completed']);
                ActivityLog::log('booking_auto_completed', "Booking auto-completed after event end time: {$booking->reference_no}", 'Guest Bookings');
                $completed++;
            });

        $this->info("Done. {$completed} booking(s) auto-completed.");
        return self::SUCCESS;
    }
}
