<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\EventReservation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventReservationController extends Controller
{
    public function index()
    {
        $reservations = EventReservation::with(['user', 'event', 'venue', 'package'])
            ->latest()->get();
        return view('staff.event-reservations.index', compact('reservations'));
    }

    public function show($id)
    {
        $reservation = EventReservation::with(['user', 'event', 'venue', 'package'])
            ->findOrFail($id);
        return view('staff.event-reservations.show', compact('reservation'));
    }

    public function confirm($id)
    {
        $reservation = EventReservation::with(['user', 'event'])->findOrFail($id);
        $reservation->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
        ]);

        ActivityLog::log(
            'reservation_confirmed',
            Auth::user()->name . " confirmed reservation #{$id} for {$reservation->user->name} ({$reservation->event->name})",
            'Reservations'
        );

        return redirect()->route('staff.event-reservations.index')
            ->with('success', 'Reservation confirmed successfully!');
    }

    public function complete($id)
    {
        $reservation = EventReservation::with(['user', 'event'])->findOrFail($id);
        $reservation->update(['status' => 'completed']);

        ActivityLog::log(
            'reservation_completed',
            Auth::user()->name . " marked reservation #{$id} as completed ({$reservation->user->name} - {$reservation->event->name})",
            'Reservations'
        );

        return redirect()->route('staff.event-reservations.index')
            ->with('success', 'Reservation marked as completed!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $reservation = EventReservation::with(['user', 'event'])->findOrFail($id);
        $oldStatus   = $reservation->status;
        $reservation->update([
            'status'       => $request->status,
            'confirmed_at' => $request->status === 'confirmed' ? now() : $reservation->confirmed_at,
        ]);

        ActivityLog::log(
            'reservation_status_updated',
            Auth::user()->name . " changed reservation #{$id} status from {$oldStatus} to {$request->status} ({$reservation->user->name})",
            'Reservations'
        );

        return redirect()->route('staff.event-reservations.index')
            ->with('success', 'Reservation status updated!');
    }

    public function destroy($id)
    {
        $reservation = EventReservation::with(['user', 'event'])->findOrFail($id);
        $info = "#{$id} - {$reservation->user->name} ({$reservation->event->name})";
        $reservation->delete();

        ActivityLog::log(
            'reservation_deleted',
            Auth::user()->name . " deleted reservation {$info}",
            'Reservations'
        );

        return redirect()->route('staff.event-reservations.index')
            ->with('success', 'Reservation deleted!');
    }

    public function receipt($id)
    {
        $reservation = EventReservation::with(['user', 'event', 'venue', 'package'])
            ->findOrFail($id);

        ActivityLog::log(
            'receipt_printed',
            Auth::user()->name . " printed receipt for reservation #{$id} ({$reservation->user->name})",
            'Reservations'
        );

        return view('staff.event-reservations.receipt', compact('reservation'));
    }
}