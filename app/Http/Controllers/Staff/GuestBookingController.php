<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\GuestBooking;
use Illuminate\Http\Request;

class GuestBookingController extends Controller
{
    public function index()
    {
        $bookings = GuestBooking::with(['event', 'venue'])->latest()->paginate(20);
        $stats = [
            'total'     => GuestBooking::count(),
            'pending'   => GuestBooking::where('status', 'pending')->count(),
            'confirmed' => GuestBooking::where('status', 'confirmed')->count(),
            'completed' => GuestBooking::where('status', 'completed')->count(),
            'cancelled' => GuestBooking::where('status', 'cancelled')->count(),
        ];
        return view('staff.guest-bookings.index', compact('bookings', 'stats'));
    }

    public function show(GuestBooking $guestBooking)
    {
        return view('staff.guest-bookings.show', ['booking' => $guestBooking->load(['event', 'venue'])]);
    }

    public function confirm(GuestBooking $guestBooking)
    {
        $guestBooking->update(['status' => 'confirmed']);
        return back()->with('success', 'Booking confirmed successfully.');
    }

    public function cancel(GuestBooking $guestBooking)
    {
        $guestBooking->update(['status' => 'cancelled']);
        return back()->with('success', 'Booking cancelled.');
    }

    public function complete(GuestBooking $guestBooking)
    {
        $guestBooking->update(['status' => 'completed']);
        return back()->with('success', 'Booking marked as completed.');
    }

    public function updatePayment(Request $request, GuestBooking $guestBooking)
    {
        $request->validate([
            'amount_paid'    => 'required|numeric|min:0',
            'payment_status' => 'required|in:unpaid,partial,paid',
        ]);

        $guestBooking->update([
            'amount_paid'    => $request->amount_paid,
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Payment status updated successfully.');
    }
}