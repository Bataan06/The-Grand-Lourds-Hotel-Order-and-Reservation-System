<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Package;

class EventDetailController extends Controller
{
    public function show($slug)
    {
        $events = Event::where('is_active', true)->get();

        $event = $events->first(function($e) use ($slug) {
            $eventSlug = strtolower(str_replace([' ', '/'], '-', $e->name));
            return $eventSlug === strtolower($slug) ||
                   str_contains($eventSlug, strtolower($slug)) ||
                   str_contains(strtolower($e->name), strtolower(str_replace('-', ' ', $slug)));
        });

        if (!$event) abort(404);

        $packages = Package::with('venue')
            ->where('event_id', $event->id)
            ->where('is_active', true)
            ->orderBy('pax_min')
            ->get();

        return view('guest.event-detail', compact('event', 'packages'));
    }
}