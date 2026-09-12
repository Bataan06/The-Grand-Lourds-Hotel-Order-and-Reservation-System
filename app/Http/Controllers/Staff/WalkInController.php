<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Package;
use App\Models\GuestBooking;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WalkInController extends Controller
{
    public function create()
    {
        $events = Event::where('is_active', true)
            ->with(['packages' => function($q) {
                $q->where('is_active', true)->with('venue')->orderBy('pax_min');
            }])->get();

        // Pre-build eventsData para sa JS sa blade (hindi pwede closure sa @json)
        $eventsData = [];
        foreach ($events as $event) {
            $packages = [];
            foreach ($event->packages as $pkg) {
                $packages[] = [
                    'id'        => $pkg->id,
                    'venueName' => $pkg->venue->name ?? '—',
                    'paxMin'    => $pkg->pax_min,
                    'paxMax'    => $pkg->pax_max,
                    'paxRange'  => ($pkg->pax_min ?? '?') . '–' . ($pkg->pax_max ?? '?'),
                    'tiers'     => $pkg->price_tiers ?? [],
                ];
            }
            $eventsData[$event->id] = [
                'id'       => $event->id,
                'name'     => $event->name,
                'packages' => $packages,
            ];
        }

        return view('staff.walk-in.create', compact('events', 'eventsData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_name'       => 'required|string|max:255',
            'guest_phone'      => 'required|string|max:20',
            'guest_email'      => 'nullable|email|max:255',
            'event_id'         => 'required|exists:events,id',
            'package_id'       => 'required|exists:packages,id',
            'celebrant_name'   => 'required|string|max:255',
            'pax_count'        => 'required|integer|min:1',
            'event_date'       => 'required|date',
            'event_time_start' => 'required',
            'food_set'         => 'required|string',
            'price_per_pax'    => 'required|numeric',
            // ✅ FIX: nullable — hindi lahat ng food set ay may soup/dessert/drink choices
            'soup_choice'      => 'nullable|string',
            'dessert_choice'   => 'nullable|string',
            'drink_choice'     => 'nullable|string',
        ]);

        $package     = Package::findOrFail($request->package_id);
        $pricePerPax = $request->price_per_pax;
        $grandTotal  = ($pricePerPax * $request->pax_count) + (float)($request->addon_total ?? 0);

        // ✅ FIX: Time overlap conflict detection (4-hour window)
        $newStart = Carbon::parse($request->event_time_start);
        $newEnd   = $newStart->copy()->addHours(4);

        $conflictingBookings = GuestBooking::whereDate('event_date', $request->event_date)
            // Pending and already-pencil reservations both reserve the slot.
            ->whereIn('status', ['pending', 'pencil'])
            ->whereRaw("event_time_start < ?", [$newEnd->format('H:i:s')])
            ->whereRaw("ADDTIME(event_time_start, '04:00:00') > ?", [$newStart->format('H:i:s')])
            ->get();

        $conflict       = $conflictingBookings->isNotEmpty();
        $conflictWithId = $conflictingBookings->first()?->id ?? null;

        $addons = [];
        if ($request->has('addons')) {
            foreach ($request->addons as $key => $data) {
                if (!empty($data['selected'])) {
                    $addons[] = [
                        'key'   => $key,
                        'price' => $data['price'] ?? 0,
                        'qty'   => $data['qty'] ?? 1,
                    ];
                }
            }
        }

        // Only include choices na may value
        $parts = [];
        if ($request->soup_choice)      $parts[] = "Soup: {$request->soup_choice}";
        if ($request->dessert_choice)   $parts[] = "Dessert: {$request->dessert_choice}";
        if ($request->drink_choice)     $parts[] = "Drink: {$request->drink_choice}";
        if ($request->special_requests) $parts[] = "Notes: {$request->special_requests}";
        $specialRequests = implode(' | ', $parts);

        $refNo = 'GLH-' . strtoupper(substr(md5(uniqid()), 0, 8));

        $booking = GuestBooking::create([
            'reference_no'         => $refNo,
            'guest_name'           => $request->guest_name,
            'guest_phone'          => $request->guest_phone,
            'guest_email'          => $request->guest_email,
            'event_id'             => $request->event_id,
            'package_id'           => $request->package_id,
            'venue_id'             => $package->venue_id,
            'celebrant_name'       => $request->celebrant_name,
            'pax_count'            => $request->pax_count,
            'event_date'           => $request->event_date,
            'event_time_start'     => $request->event_time_start,
            'food_set'             => $request->food_set,
            'price_per_pax'        => $pricePerPax,
            'total_amount'         => $grandTotal,
            'additional_charges'   => json_encode($addons),
            'additional_total'     => (float)($request->addon_total ?? 0),
            'special_requests'     => $specialRequests,
            'status'               => $conflict ? 'pencil' : 'pending',
            'has_conflict'         => $conflict,
            'is_pencil'            => $conflict ? true : $request->boolean('is_pencil'),
            'conflict_with_id'     => $conflictWithId,
            'payment_proof_status' => 'none',
        ]);

        if ($conflict) {
            GuestBooking::whereIn('id', $conflictingBookings->pluck('id'))
                ->update([
                    'status'           => 'pencil',
                    'is_pencil'        => true,
                    'has_conflict'     => true,
                    'conflict_with_id' => $booking->id,
                ]);
        }

        ActivityLog::log(
            'walk_in_booking',
            auth()->user()->name . " recorded a walk-in booking for {$request->guest_name} ({$request->guest_phone}) — Ref# {$refNo}" .
            ($conflict ? " ⚠️ CONFLICT detected. Auto-set to PENCIL." : ""),
            'Walk-in Bookings'
        );

        return redirect()->route('staff.guest-bookings.index')
            ->with('success', "Walk-in booking recorded! Ref# {$refNo}" .
                ($conflict ? " ⚠️ Conflict detected — booking set to PENCIL." : ""));
    }

    // AJAX conflict check
    public function checkConflict(Request $request)
    {
        $packageId = $request->package_id;
        $date      = $request->date;
        $time      = $request->time;

        if (!$packageId || !$date) {
            return response()->json(['conflict' => false]);
        }

        $package = Package::find($packageId);
        if (!$package) return response()->json(['conflict' => false]);

        $query = GuestBooking::whereDate('event_date', $date)
            ->whereIn('status', ['pending', 'pencil']);

        if ($time) {
            $start = Carbon::parse($time);
            $end   = $start->copy()->addHours(4);
            $query->whereRaw("event_time_start < ?", [$end->format('H:i:s')])
                  ->whereRaw("ADDTIME(event_time_start, '04:00:00') > ?", [$start->format('H:i:s')]);
        }

        return response()->json(['conflict' => $query->exists()]);
    }
}
