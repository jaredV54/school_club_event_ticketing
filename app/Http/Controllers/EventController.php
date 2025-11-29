<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Club;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $events = Event::with('club')->get();
        } elseif ($user->role === 'officer' && $user->club_id) {
            // Officers can only see events from their club
            $events = Event::with('club')->where('club_id', $user->club_id)->get();
        } else {
            // Students can view all events
            $events = Event::with('club')->get();
        }

        return view('events.index', compact('events'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $clubs = Club::all();
        } elseif ($user->role === 'officer' && $user->club_id) {
            // Officers can only create events for their club
            $clubs = Club::where('id', $user->club_id)->get();
        } else {
            abort(403, 'Unauthorized access.');
        }

        return view('events.create', compact('clubs'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'required|string|max:255',
            'date' => 'required|date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'capacity' => 'required|integer|min:1',
        ]);

        // Check if officer is trying to create event for their club
        if ($user->role === 'officer' && $user->club_id !== (int)$request->club_id) {
            abort(403, 'You can only create events for your own club.');
        }

        Event::create($request->all());

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $event->load('club', 'registrations.user');
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $user = auth()->user();

        // Check if officer owns this event
        if ($user->role === 'officer' && $event->club_id !== $user->club_id) {
            abort(403, 'You can only edit events for your own club.');
        }

        if ($user->role === 'admin') {
            $clubs = Club::all();
        } elseif ($user->role === 'officer') {
            $clubs = Club::where('id', $user->club_id)->get();
        } else {
            abort(403, 'Unauthorized access.');
        }

        return view('events.edit', compact('event', 'clubs'));
    }

    public function update(Request $request, Event $event)
    {
        $user = auth()->user();

        // Check if officer owns this event
        if ($user->role === 'officer' && $event->club_id !== $user->club_id) {
            abort(403, 'You can only edit events for your own club.');
        }

        $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'required|string|max:255',
            'date' => 'required|date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'capacity' => 'required|integer|min:1',
        ]);

        // Check if officer is trying to change club
        if ($user->role === 'officer' && $event->club_id !== (int)$request->club_id) {
            abort(403, 'You cannot change the club for this event.');
        }

        $event->update($request->all());

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $user = auth()->user();

        // Check if officer owns this event
        if ($user->role === 'officer' && $event->club_id !== $user->club_id) {
            abort(403, 'You can only delete events for your own club.');
        }

        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}