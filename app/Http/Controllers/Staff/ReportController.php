<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\EventReservation;
use App\Models\Event;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        $reservations = EventReservation::with(['user', 'event', 'venue', 'package'])
            ->whereRaw("DATE_FORMAT(event_date, '%Y-%m') = ?", [$month])
            ->latest()
            ->get();

        $summary = [
            'total'     => $reservations->count(),
            'pending'   => $reservations->where('status', 'pending')->count(),
            'confirmed' => $reservations->where('status', 'confirmed')->count(),
            'completed' => $reservations->where('status', 'completed')->count(),
            'cancelled' => $reservations->where('status', 'cancelled')->count(),
        ];

        $byEvent = $reservations->groupBy('event.name')->map->count();

        return view('staff.reports.index', compact('reservations', 'summary', 'byEvent', 'month'));
    }
}