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
        // Officers have full access like admin

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

        $events = $query->orderBy('created_at', 'desc')->get();
        $clubs = Club::all();

        return view('events.index', compact('events', 'clubs'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->role === 'admin' || $user->role === 'officer') {
            $clubs = Club::all();
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

        // Officers have full access like admin

        Event::create($request->all());

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $user = auth()->user();
        
        // Officers have full access like admin
        
        $event->load('club', 'registrations.user');
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $user = auth()->user();

        if ($user->role === 'admin' || $user->role === 'officer') {
            $clubs = Club::all();
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

        // Officers have full access like admin

        $event->update($request->all());

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $user = auth()->user();

        // Officers have full access like admin

        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}
