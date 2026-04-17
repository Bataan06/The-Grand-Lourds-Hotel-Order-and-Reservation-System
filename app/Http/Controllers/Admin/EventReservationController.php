<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestBooking;
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
        $reservation = GuestBooking::with(['event', 'venue'])->findOrFail($id);
        return view('admin.event-reservations.show', compact('reservation'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $reservation = GuestBooking::findOrFail($id);
        $reservation->update(['status' => $request->status]);

        return redirect()->route('admin.event-reservations.index')
            ->with('success', 'Reservation status updated!');
    }

    public function destroy($id)
    {
        GuestBooking::findOrFail($id)->delete();
        return redirect()->route('admin.event-reservations.index')
            ->with('success', 'Reservation deleted!');
    }
}