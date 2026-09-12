<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\GuestBooking;
use App\Services\BookingConflictService;
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
        return view('staff.guest-bookings.show', [
            'booking' => $guestBooking->load(['event', 'venue', 'package'])
        ]);
    }

    public function history()
    {
        $bookings = GuestBooking::with(['event', 'venue'])
            ->where(function ($query) {
                $query->whereIn('status', ['completed', 'cancelled'])
                    ->orWhereDate('event_date', '<', today());
            })
            ->latest()
            ->paginate(20);

        $stats = [
            'total'     => $bookings->total(),
            'pending'   => 0,
            'confirmed' => 0,
            'completed' => GuestBooking::where('status', 'completed')->count(),
            'cancelled' => GuestBooking::where('status', 'cancelled')->count(),
        ];

        return view('staff.guest-bookings.index', compact('bookings', 'stats'))->with('historyMode', true);
    }

    public function update(Request $request, GuestBooking $guestBooking)
    {
        $request->validate([
            'guest_name'       => 'required|string|max:255',
            'guest_phone'      => 'required|string|max:20',
            'guest_email'      => 'required|email|max:255',
            'celebrant_name'   => 'required|string|max:255',
            'pax_count'        => 'required|integer|min:1',
            'event_date'       => 'required|date',
            'event_time_start' => 'required',
            'food_set'         => 'required|string',
        ]);

        $addons = [];
        $addonTotal = 0;
        if ($request->has('addons')) {
            foreach ($request->addons as $key => $data) {
                if (!empty($data['selected'])) {
                    $price = (float)($data['price'] ?? 0);
                    $qty   = (int)($data['qty'] ?? 1);
                    $addons[] = ['key' => $key, 'price' => $price, 'qty' => $qty];
                    $addonTotal += $price * $qty;
                }
            }
        }

        $pricePerPax = (float)$guestBooking->price_per_pax;
        $paxCount    = (int)$request->pax_count;
        $totalAmount = ($pricePerPax * $paxCount) + $addonTotal;

        $guestBooking->update([
            'guest_name'         => $request->guest_name,
            'guest_phone'        => $request->guest_phone,
            'guest_email'        => $request->guest_email,
            'celebrant_name'     => $request->celebrant_name,
            'pax_count'          => $paxCount,
            'event_date'         => $request->event_date,
            'event_time_start'   => $request->event_time_start,
            'food_set'           => $request->food_set,
            'special_requests'   => $request->special_requests,
            'additional_charges' => json_encode($addons),
            'additional_total'   => $addonTotal,
            'total_amount'       => $totalAmount,
        ]);

        return back()->with('success', 'Booking updated successfully.');
    }

    public function confirm(GuestBooking $guestBooking)
    {
        $this->confirmBooking($guestBooking);

        return back()->with('success', 'Booking confirmed. Guest can now upload payment proof.');
    }

    /**
     * A partial or full payment secures a Pencil reservation.  The booking is
     * confirmed and any other still-unpaid overlapping request remains Pencil.
     */
    private function confirmBooking(GuestBooking $guestBooking): void
    {
        app(BookingConflictService::class)->confirm($guestBooking);
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

        if (in_array($request->payment_status, ['partial', 'paid'], true)) {
            $this->confirmBooking($guestBooking->fresh());

            return back()->with('success', 'Payment updated and booking automatically confirmed.');
        }

        return back()->with('success', 'Payment updated successfully.');
    }

    public function verifyPayment(GuestBooking $guestBooking)
    {
        $guestBooking->update([
            'payment_proof_status' => 'verified',
            'payment_status'       => 'partial',
            'amount_paid'          => $guestBooking->total_amount * 0.5,
        ]);

        $this->confirmBooking($guestBooking->fresh());

        return back()->with('success', 'Payment verified! Booking is partially paid and automatically confirmed.');
    }

    public function rejectPayment(GuestBooking $guestBooking)
    {
        $guestBooking->update([
            'payment_proof_status' => 'rejected',
        ]);

        return back()->with('success', 'Payment proof rejected. Guest will be notified to re-upload.');
    }
}
