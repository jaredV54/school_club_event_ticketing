<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Club;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Event::with('club');

        // Apply role-based filtering
        if ($user->role === 'officer' && $user->club_id) {
            // Officers can only see their club's events
            $query->where('club_id', $user->club_id);
        }

        // Apply filters
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('club_id')) {
            $query->where('club_id', $request->club_id);
        }

        if ($request->filled('venue')) {
            $query->where('venue', 'like', '%' . $request->venue . '%');
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        if ($request->filled('capacity_min')) {
            $query->where('capacity', '>=', $request->capacity_min);
        }

        if ($request->filled('capacity_max')) {
            $query->where('capacity', '<=', $request->capacity_max);
        }

        if ($request->filled('registration_rate_min')) {
            $query->whereRaw('(SELECT COUNT(*) FROM event_registrations WHERE event_registrations.event_id = events.id) / capacity * 100 >= ?', [$request->registration_rate_min]);
        }

        if ($request->filled('registration_rate_max')) {
            $query->whereRaw('(SELECT COUNT(*) FROM event_registrations WHERE event_registrations.event_id = events.id) / capacity * 100 <= ?', [$request->registration_rate_max]);
        }

        $events = $query->get();
        $clubs = Club::all();

        return view('events.index', compact('events', 'clubs'));
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

        // Officers can only create events for their club
        if ($user->role === 'officer' && $user->club_id != $request->club_id) {
            abort(403, 'You can only create events for your club.');
        }

        Event::create($request->all());

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $user = auth()->user();
        
        // Officers should only view their club's events
        if ($user->role === 'officer' && $user->club_id) {
            if ($event->club_id !== $user->club_id) {
                abort(403, 'You can only view your club\'s events.');
            }
        }
        
        $event->load('club', 'registrations.user');
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $clubs = Club::all();
        } elseif ($user->role === 'officer' && $user->club_id) {
            // Officers can only edit events from their club
            if ($event->club_id != $user->club_id) {
                abort(403, 'You can only edit events from your club.');
            }
            $clubs = Club::where('id', $user->club_id)->get();
        } else {
            abort(403, 'Unauthorized access.');
        }

        return view('events.edit', compact('event', 'clubs'));
    }

    public function update(Request $request, Event $event)
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

        // Officers can only update events from their club
        if ($user->role === 'officer' && $user->club_id != $event->club_id) {
            abort(403, 'You can only update events from your club.');
        }

        $event->update($request->all());

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $user = auth()->user();

        // Officers can only delete events from their club
        if ($user->role === 'officer' && $user->club_id != $event->club_id) {
            abort(403, 'You can only delete events from your club.');
        }

        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}
