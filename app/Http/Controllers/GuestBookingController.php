<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Package;
use App\Models\GuestBooking;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

    // ══════════════════════════════════════════════════════════════════
    // AJAX: Check Availability
    // Called by the booking form when guest selects date + time
    // Returns: 
    //   - If check_times=1: Array of booked times for the date
    //   - Otherwise: ok / warning (pencil) / blocked (confirmed)
    // ══════════════════════════════════════════════════════════════════
    public function checkAvailability(Request $request)
    {
        $venueId   = $request->venue_id;
        $date      = $request->event_date;
        $checkTimes = $request->check_times; // New: flag to return all booked times
        
        if (!$venueId || !$date) {
            return response()->json(['status' => 'ok', 'booked_times' => []]);
        }

        // ── NEW: Return all booked times for this date/venue ──
        if ($checkTimes) {
            $confirmedBookings = GuestBooking::whereDate('event_date', $date)
                ->where('status', 'confirmed')
                ->pluck('event_time_start')
                ->map(function($time) {
                    return Carbon::parse($time)->format('H:i');
                })
                ->toArray();
            
            return response()->json(['booked_times' => $confirmedBookings]);
        }

        // ── Original logic: Check a specific time slot ──
        $timeStart = $request->event_time_start;
        if (!$timeStart) {
            return response()->json(['status' => 'ok']);
        }

        $newStart = Carbon::parse($timeStart);
        $newEnd   = $newStart->copy()->addHours(4);

        // ── Priority check: May CONFIRMED na ba sa overlapping slot? ──
        // Kung oo, blocked agad — hindi na titingnan pa ang iba
        $confirmedConflict = GuestBooking::whereDate('event_date', $date)
            ->where('status', 'confirmed')
            ->whereRaw("TIME(event_time_start) < ?", [$newEnd->format('H:i:s')])
            ->whereRaw("ADDTIME(TIME(event_time_start), '04:00:00') > ?", [$newStart->format('H:i:s')])
            ->first();

        if ($confirmedConflict) {
            $takenTime = Carbon::parse($confirmedConflict->event_time_start)->format('g:i A');
            return response()->json([
                'status'  => 'blocked',
                'message' => "This venue is already confirmed booked at {$takenTime} on this date. Please choose a different date or time.",
            ]);
        }

        // ── Check: May PENDING o PENCIL na ba sa overlapping slot? ──
        // Kung oo, pwede pa mag-book pero magiging Pencil
        $pendingConflict = GuestBooking::whereDate('event_date', $date)
            ->whereIn('status', ['pending', 'pencil'])
            ->whereRaw("TIME(event_time_start) < ?", [$newEnd->format('H:i:s')])
            ->whereRaw("ADDTIME(TIME(event_time_start), '04:00:00') > ?", [$newStart->format('H:i:s')])
            ->first();

        if ($pendingConflict) {
            $takenTime = Carbon::parse($pendingConflict->event_time_start)->format('g:i A');
            return response()->json([
                'status'  => 'warning',
                'message' => "There is already a pending booking at {$takenTime} on this date. Your booking will be placed under ✏️ Pencil status. Our staff will contact you to finalize the arrangement.",
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_name'       => 'required|string|max:255',
            'guest_phone'      => 'required|string|max:20',
            'guest_email'      => 'required|email|max:255',
            'event_id'         => 'required|exists:events,id',
            'package_id'       => 'required|exists:packages,id',
            'celebrant_name'   => 'required|string|max:255',
            'pax_count'        => 'required|integer|min:1',
            'event_date'       => 'required|date|after:today',
            'food_set'         => 'required|string',
            'price_per_pax'    => 'required|numeric',
            'soup_choice'      => 'nullable|string',
            'dessert_choice'   => 'nullable|string',
            'drink_choice'     => 'nullable|string',
        ]);

        $package = Package::with('venue')->findOrFail($request->package_id);

        // Never trust price or package data submitted by the browser.
        if ((int) $package->event_id !== (int) $request->event_id) {
            return back()->withInput()->withErrors(['package_id' => 'The selected package does not belong to this event.']);
        }

        $pricePerPax = null;
        $selectedTier = null;
        foreach ($package->price_tiers ?? [] as $tierPrice => $sets) {
            if ((float) $tierPrice === (float) $request->price_per_pax) {
                $pricePerPax = (float) $tierPrice;
                $selectedTier = $sets;
                break;
            }
        }
        if ($pricePerPax === null || !isset($selectedTier[$request->food_set])) {
            return back()->withInput()->withErrors(['food_set' => 'Please select a valid package price and food set.']);
        }

        $newStart = Carbon::parse($request->event_time_start);
        $newEnd   = $newStart->copy()->addHours(4);

        // ══════════════════════════════════════════════════════════════
        // SLOT CHECK
        // - Confirmed booking sa same slot = BLOCKED
        // - Pending/Pencil sa same slot    = pwede, bagong booking = Pencil
        // - Max 2 bookings per venue per day
        // ══════════════════════════════════════════════════════════════

        // Check 1: May CONFIRMED na sa exact overlapping slot?
        $confirmedConflict = GuestBooking::whereDate('event_date', $request->event_date)
            ->where('status', 'confirmed')
            ->whereRaw("TIME(event_time_start) < ?", [$newEnd->format('H:i:s')])
            ->whereRaw("ADDTIME(TIME(event_time_start), '04:00:00') > ?", [$newStart->format('H:i:s')])
            ->first();

        if ($confirmedConflict) {
            $takenTime = Carbon::parse($confirmedConflict->event_time_start)->format('g:i A');
            $takenDate = Carbon::parse($confirmedConflict->event_date)->format('F d, Y');
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'event_date' => "Sorry, {$package->venue->name} is already confirmed booked on {$takenDate} at {$takenTime}. Please choose a different date or time slot.",
                ]);
        }

        // Check 2: Max 2 bookings per venue per day
        $dailyCount = GuestBooking::where('venue_id', $package->venue_id)
            ->whereDate('event_date', $request->event_date)
            ->whereIn('status', ['pending', 'confirmed', 'pencil'])
            ->count();

        if ($dailyCount >= 2) {
            $takenDate = Carbon::parse($request->event_date)->format('F d, Y');
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'event_date' => "Sorry, {$package->venue->name} is fully booked on {$takenDate}. Maximum of 2 events per day (morning & evening). Please choose a different date.",
                ]);
        }

        // Check 3: May PENDING o PENCIL ba sa overlapping slot?
        // Kapag oo — itong bagong booking ay magiging Pencil din
        $pendingConflict = GuestBooking::whereDate('event_date', $request->event_date)
            ->whereIn('status', ['pending', 'pencil'])
            ->whereRaw("TIME(event_time_start) < ?", [$newEnd->format('H:i:s')])
            ->whereRaw("ADDTIME(TIME(event_time_start), '04:00:00') > ?", [$newStart->format('H:i:s')])
            ->first();

        $isPencil      = $pendingConflict ? true : false;
        $bookingStatus = $isPencil ? 'pencil' : 'pending'; // ← FIXED: status mismo ay pencil na

        // ── ADDONS ────────────────────────────────────────────────────
        $allowedAddons = [
            'grazing_table' => ['type' => 'tier', 'prices' => [10000, 15000, 20000, 25000, 30000]],
            'shabu_station' => ['type' => 'tier', 'prices' => [12500, 15000, 17500, 20000]],
            'upgraded_setup' => ['type' => 'fixed', 'price' => 15000],
            'photography' => ['type' => 'fixed', 'price' => 30000],
            'photo_booth' => ['type' => 'fixed', 'price' => 4500],
            'led_wall' => ['type' => 'fixed', 'price' => 15000],
            'lechon_baboy' => ['type' => 'qty', 'price' => 1000],
            'sweet_buffet' => ['type' => 'fixed', 'price' => 5000],
            'outside_stylist' => ['type' => 'fixed', 'price' => 2000],
            'full_band' => ['type' => 'fixed', 'price' => 3000],
            'liquor_bottle' => ['type' => 'qty', 'price' => 200],
            'liquor_case' => ['type' => 'qty', 'price' => 500],
            'food_cart' => ['type' => 'fixed', 'price' => 1500],
            'photo_booth_elec' => ['type' => 'fixed', 'price' => 1000],
            'exceeding_hour' => ['type' => 'fixed', 'price' => 3000],
        ];
        foreach ($package->additional_options ?? [] as $option) {
            if (!empty($option['key']) && isset($option['price'])) {
                $allowedAddons[$option['key']] = ['type' => $option['type'] ?? 'fixed', 'price' => (float) $option['price']];
            }
        }

        $addons = [];
        $addonTotal = 0;
        foreach ($request->input('addons', []) as $key => $data) {
            if (empty($data['selected']) || !isset($allowedAddons[$key])) continue;
            $rule = $allowedAddons[$key];
            $quantity = min(max((int) ($data['qty'] ?? 1), 1), 999);
            $unitPrice = $rule['type'] === 'tier'
                ? (float) ($data['price'] ?? 0)
                : (float) ($rule['price'] ?? 0);
            if ($rule['type'] === 'tier' && !in_array((int) $unitPrice, $rule['prices'], true)) continue;
            $lineTotal = $rule['type'] === 'pax' ? $unitPrice * $request->pax_count : ($rule['type'] === 'qty' ? $unitPrice * $quantity : $unitPrice);
            $addons[] = ['key' => $key, 'price' => $unitPrice, 'qty' => $rule['type'] === 'qty' ? $quantity : 1];
            $addonTotal += $lineTotal;
        }
        $grandTotal = ($pricePerPax * $request->pax_count) + $addonTotal;

        $parts = [];
        if ($request->soup_choice)      $parts[] = "Soup: {$request->soup_choice}";
        if ($request->dessert_choice)   $parts[] = "Dessert: {$request->dessert_choice}";
        if ($request->drink_choice)     $parts[] = "Drink: {$request->drink_choice}";
        if ($request->special_requests) $parts[] = "Notes: {$request->special_requests}";
        $specialRequests = implode(' | ', $parts);

        $refNo = 'GLH-' . Str::upper(Str::random(24));

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
            'additional_total'     => $addonTotal,
            'special_requests'     => $specialRequests,
            'status'               => $bookingStatus, // ← 'pencil' or 'pending'
            'has_conflict'         => $isPencil,
            'is_pencil'            => $isPencil,
            'conflict_with_id'     => $pendingConflict?->id ?? null,
            'payment_proof_status' => 'none',
        ]);

        // ── AUTO-PENCIL: I-update din ang existing pending conflict as Pencil ──
        // Para parehong Pencil sila — hindi lang yung bago
        if ($isPencil && $pendingConflict && $pendingConflict->status === 'pending') {
            $pendingConflict->update([
                'status'           => 'pencil',
                'is_pencil'        => true,
                'has_conflict'     => true,
                'conflict_with_id' => $booking->id,
            ]);
        }

        ActivityLog::log(
            'guest_booking',
            "Guest booking submitted: {$request->guest_name} ({$request->guest_phone}) — Ref# {$refNo}" . ($isPencil ? ' [Pencil]' : ''),
            'Guest Bookings'
        );

        return redirect()->route('guest.book.success', ['ref' => $refNo]);
    }

    public function success(Request $request)
    {
        $booking = GuestBooking::where('reference_no', $request->ref)->firstOrFail();
        return view('guest.book-success', compact('booking'));
    }

    public function paymentPage($ref)
    {
        $booking = GuestBooking::where('reference_no', strtoupper($ref))
            ->with(['event', 'venue'])
            ->firstOrFail();

        return view('guest.payment', compact('booking'));
    }

    public function uploadPayment(Request $request, $ref)
    {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'payment_proof.required' => 'Please upload your payment screenshot.',
            'payment_proof.mimes'    => 'Only JPG, PNG, or PDF files are allowed.',
            'payment_proof.max'      => 'File size must not exceed 5MB.',
        ]);

        $booking = GuestBooking::where('reference_no', strtoupper($ref))->firstOrFail();

        if (!in_array($booking->status, ['pending', 'confirmed', 'pencil'])) {
            return back()->with('error', 'This booking cannot accept payment uploads at this time.');
        }

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $booking->update([
            'payment_proof'        => $path,
            'payment_proof_status' => 'submitted',
        ]);

        ActivityLog::log(
            'payment_upload',
            "Payment proof uploaded for booking {$ref} by {$booking->guest_name}",
            'Guest Bookings'
        );

        return back()->with('success', 'Payment proof uploaded successfully! Our staff will verify it shortly.');
    }

    // ══════════════════════════════════════════════════════════════════
    // STAFF: Confirm a booking
    // - I-confirm ang piniling booking
    // - Lahat ng PENDING/PENCIL na nag-o-overlap sa same venue+date+time
    //   ay awtomatikong magiging PENCIL
    // ══════════════════════════════════════════════════════════════════
    public function confirm($id)
    {
        $booking = GuestBooking::findOrFail($id);

        $confirmedStart = Carbon::parse($booking->event_time_start);
        $confirmedEnd   = $confirmedStart->copy()->addHours(4);

        // ── I-update lahat ng conflicting bookings → Pencil ──
        // Bago i-confirm ang booking, hanapin lahat ng PENDING/PENCIL
        // na nag-o-overlap sa same venue + date + time slot
        $conflictingBookings = GuestBooking::whereDate('event_date', $booking->event_date)
            ->whereIn('status', ['pending', 'pencil'])
            ->where('id', '!=', $booking->id)
            ->whereRaw("TIME(event_time_start) < ?", [$confirmedEnd->format('H:i:s')])
            ->whereRaw("ADDTIME(TIME(event_time_start), '04:00:00') > ?", [$confirmedStart->format('H:i:s')])
            ->get();

        foreach ($conflictingBookings as $conflict) {
            $conflict->update([
                'status'           => 'pencil',
                'is_pencil'        => true,
                'has_conflict'     => true,
                'conflict_with_id' => $booking->id,
            ]);

            ActivityLog::log(
                'booking_penciled',
                "Booking #{$conflict->reference_no} ({$conflict->guest_name}) moved to Pencil due to confirmation of #{$booking->reference_no}.",
                'Guest Bookings'
            );
        }

        // ── I-confirm na ang booking ──
        $booking->update([
            'status'           => 'confirmed',
            'is_pencil'        => false,
            'has_conflict'     => false,
            'conflict_with_id' => null,
            'confirmed_at'     => now(),
        ]);

        ActivityLog::log(
            'booking_confirmed',
            "Booking #{$booking->reference_no} ({$booking->guest_name}) confirmed by staff.",
            'Guest Bookings'
        );

        $pencilCount = $conflictingBookings->count();
        $pencilNote  = $pencilCount > 0
            ? " {$pencilCount} conflicting booking(s) moved to Pencil status."
            : '';

        return back()->with('success', "✅ Booking #{$booking->reference_no} confirmed!{$pencilNote}");
    }
}
