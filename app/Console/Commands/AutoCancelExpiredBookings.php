<?php

namespace App\Console\Commands;

use App\Models\GuestBooking;
use App\Models\ActivityLog;
use Illuminate\Console\Command;

class AutoCancelExpiredBookings extends Command
{
    protected $signature   = 'bookings:auto-cancel';
    protected $description = 'Auto-cancel pending bookings that have not paid within 24 hours';

    public function handle()
    {
        // Find all pending bookings older than 24 hours with no downpayment
        $expired = GuestBooking::where('status', 'pending')
            ->where('payment_status', 'unpaid')
            ->where('created_at', '<=', now()->subHours(24))
            ->get();

        foreach ($expired as $booking) {
            $booking->update(['status' => 'cancelled']);

            ActivityLog::log(
                'auto_cancel',
                "Booking auto-cancelled (no downpayment within 24 hrs): {$booking->guest_name} — Ref# {$booking->reference_no}",
                'Guest Bookings'
            );

            $this->info("Auto-cancelled: {$booking->reference_no} — {$booking->guest_name}");
        }

        $this->info("Done. {$expired->count()} booking(s) auto-cancelled.");
    }
}