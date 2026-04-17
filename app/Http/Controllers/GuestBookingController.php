<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Package;
use App\Models\GuestBooking;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class GuestBookingController extends Controller
{
    public function show($eventSlug = null)
    {
        $events = Event::where('is_active', true)->with(['packages' => function($q) {
            $q->where('is_active', true)->with('venue')->orderBy('pax_min');
        }])->get();

        $selectedEvent = null;
        if ($eventSlug) {
            $selectedEvent = $events->first(function($e) use ($eventSlug) {
                $slug = strtolower(str_replace([' ', '/'], '-', $e->name));
                return str_contains($slug, strtolower($eventSlug)) ||
                       str_contains(strtolower($e->name), strtolower(str_replace('-', ' ', $eventSlug)));
            });
        }
        if (!$selectedEvent) $selectedEvent = $events->first();

        $selectedPackageId = request('pkg');

        return view('guest.book', compact('events', 'selectedEvent', 'selectedPackageId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_name'     => 'required|string|max:255',
            'guest_phone'    => 'required|string|max:20',
            'guest_email'    => 'required|email|max:255',
            'event_id'       => 'required|exists:events,id',
            'package_id'     => 'required|exists:packages,id',
            'celebrant_name' => 'required|string|max:255',
            'pax_count'      => 'required|integer|min:1',
            'event_date'     => 'required|date|after:today',
            'food_set'       => 'required|string',
            'price_per_pax'  => 'required|numeric',
            'soup_choice'    => 'required|string',
            'dessert_choice' => 'required|string',
            'drink_choice'   => 'required|string',
        ]);

        $package     = Package::findOrFail($request->package_id);
        $pricePerPax = $request->price_per_pax;
        $grandTotal  = ($pricePerPax * $request->pax_count) + (float)($request->addon_total ?? 0);

        // ── Double booking detection ──────────────────────────────
        // Check if there is already a pending or confirmed booking
        // for the same venue on the same date
        $conflict = GuestBooking::where('venue_id', $package->venue_id)
            ->whereDate('event_date', $request->event_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

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

        $specialRequests = "Soup: {$request->soup_choice} | Dessert: {$request->dessert_choice} | Drink: {$request->drink_choice}";
        if ($request->special_requests) {
            $specialRequests .= " | Notes: {$request->special_requests}";
        }

        $refNo = 'GLH-' . strtoupper(substr(md5(uniqid()), 0, 8));

        GuestBooking::create([
            'reference_no'       => $refNo,
            'guest_name'         => $request->guest_name,
            'guest_phone'        => $request->guest_phone,
            'guest_email'        => $request->guest_email,
            'event_id'           => $request->event_id,
            'package_id'         => $request->package_id,
            'venue_id'           => $package->venue_id,
            'celebrant_name'     => $request->celebrant_name,
            'pax_count'          => $request->pax_count,
            'event_date'         => $request->event_date,
            'event_time_start'   => $request->event_time_start,
            'food_set'           => $request->food_set,
            'price_per_pax'      => $pricePerPax,
            'total_amount'       => $grandTotal,
            'additional_charges' => json_encode($addons),
            'additional_total'   => (float)($request->addon_total ?? 0),
            'special_requests'   => $specialRequests,
            'status'             => 'pending',
            'has_conflict'       => $conflict,
            'is_pencil'          => $request->boolean('is_pencil'),
        ]);

        ActivityLog::log(
            'guest_booking',
            "Guest booking submitted: {$request->guest_name} ({$request->guest_phone}) — Ref# {$refNo}" . ($conflict ? " ⚠️ CONFLICT: Same venue/date already booked." : ""),
            'Guest Bookings'
        );

        return redirect()->route('guest.book.success', ['ref' => $refNo]);
    }

    public function success(Request $request)
    {
        $booking = GuestBooking::where('reference_no', $request->ref)->firstOrFail();
        return view('guest.book-success', compact('booking'));
    }
}