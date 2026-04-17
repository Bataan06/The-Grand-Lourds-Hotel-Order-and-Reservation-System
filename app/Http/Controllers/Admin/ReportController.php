<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestBooking;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type  = $request->get('type', 'monthly'); // 'daily' or 'monthly'
        $month = $request->get('month', now()->format('Y-m'));
        $date  = $request->get('date', now()->format('Y-m-d'));

        if ($type === 'daily') {
            $reservations = GuestBooking::with(['event', 'venue'])
                ->whereDate('created_at', $date)
                ->latest()
                ->get();
        } else {
            $reservations = GuestBooking::with(['event', 'venue'])
                ->whereRaw("DATE_FORMAT(event_date, '%Y-%m') = ?", [$month])
                ->latest()
                ->get();
        }

        $summary = [
            'total'     => $reservations->count(),
            'pending'   => $reservations->where('status', 'pending')->count(),
            'confirmed' => $reservations->where('status', 'confirmed')->count(),
            'completed' => $reservations->where('status', 'completed')->count(),
            'cancelled' => $reservations->where('status', 'cancelled')->count(),
            'total_pax' => $reservations->sum('pax_count'),
        ];

        $byEvent   = $reservations->groupBy(fn($r) => optional($r->event)->name ?? 'Unknown')->map->count();
        $totalUsers = User::where('role', 'user')->count();

        return view('admin.reports.index', compact(
            'reservations', 'summary', 'byEvent',
            'month', 'date', 'type', 'totalUsers'
        ));
    }
}