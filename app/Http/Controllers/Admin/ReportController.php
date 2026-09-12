<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestBooking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        try {
            $selectedMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Throwable) {
            $selectedMonth = now()->startOfMonth();
            $month = $selectedMonth->format('Y-m');
        }

        // Monthly reports measure reservations made during the month. This lets
        // a newly created booking appear immediately even when its event is later.
        $reservations = GuestBooking::with(['event', 'venue'])
            ->whereBetween('created_at', [$selectedMonth, $selectedMonth->copy()->endOfMonth()])
            ->latest()
            ->get();

        $summary = [
            'total'           => $reservations->count(),
            'pending'         => $reservations->where('status', 'pending')->count(),
            'confirmed'       => $reservations->where('status', 'confirmed')->count(),
            'completed'       => $reservations->where('status', 'completed')->count(),
            'cancelled'       => $reservations->where('status', 'cancelled')->count(),
            'total_pax'       => $reservations->sum('pax_count'),
            'total_revenue'   => $reservations->whereIn('status', ['confirmed', 'completed'])->sum('total_amount'),
            'avg_pax'         => $reservations->count() > 0 ? round($reservations->avg('pax_count'), 1) : 0,
            'completion_rate' => $reservations->count() > 0
                ? round(($reservations->where('status', 'completed')->count() / $reservations->count()) * 100)
                : 0,
        ];

        $byEvent    = $reservations->groupBy(fn($r) => optional($r->event)->name ?? 'Unknown')->map->count();
        $totalUsers = User::where('role', 'user')->count();

        return view('admin.reports.index', compact(
            'reservations', 'summary', 'byEvent',
            'month', 'totalUsers'
        ));
    }
}
