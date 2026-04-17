<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $event = Event::create([
            'name'        => $request->name,
            'description' => $request->description,
            'is_active'   => $request->has('is_active'),
        ]);

        ActivityLog::log('event_created', Auth::user()->name . " created event: {$event->name}", 'Events');

        return redirect()->route('admin.events.index')
            ->with('success', "Event '{$event->name}' created!");
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $event->update([
            'name'        => $request->name,
            'description' => $request->description,
            'is_active'   => $request->has('is_active'),
        ]);

        ActivityLog::log('event_updated', Auth::user()->name . " updated event: {$event->name}", 'Events');

        return redirect()->route('admin.events.index')
            ->with('success', "Event updated!");
    }

    public function destroy(Event $event)
    {
        $name = $event->name;
        $event->delete();

        ActivityLog::log('event_deleted', Auth::user()->name . " deleted event: {$name}", 'Events');

        return redirect()->route('admin.events.index')
            ->with('success', "Event deleted!");
    }

    public function toggle(Event $event)
    {
        $event->update(['is_active' => !$event->is_active]);
        return back()->with('success', 'Event status updated!');
    }
}