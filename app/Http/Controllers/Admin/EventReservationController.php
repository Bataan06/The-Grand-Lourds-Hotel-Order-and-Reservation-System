<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestBooking;
use App\Services\BookingConflictService;
use Illuminate\Http\Request;

class EventReservationController extends Controller
{
    public function index()
    {
        $reservations = GuestBooking::with(['event', 'venue'])->latest()->get();
        return view('admin.event-reservations.index', compact('reservations'));
    }

    public function show($id)
    {
        $reservation = GuestBooking::with(['event', 'venue', 'package'])->findOrFail($id);
        return view('admin.event-reservations.show', compact('reservation'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,pencil,confirmed,cancelled,completed',
        ]);

        $reservation = GuestBooking::findOrFail($id);

        if ($request->status === 'confirmed') {
            app(BookingConflictService::class)->confirm($reservation);
        } else {
            $reservation->update([
                'status' => $request->status,
                'is_pencil' => $request->status === 'pencil',
            ]);
        }

        return redirect()->route('admin.event-reservations.index')
            ->with('success', $request->status === 'confirmed'
                ? 'Reservation confirmed. Overlapping requests were moved to Pencil.'
                : 'Reservation status updated!');
    }

    public function destroy($id)
    {
        GuestBooking::findOrFail($id)->delete();
        return redirect()->route('admin.event-reservations.index')
            ->with('success', 'Reservation deleted!');
    }
}
