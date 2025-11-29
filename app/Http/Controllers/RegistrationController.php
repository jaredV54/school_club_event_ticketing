<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $registrations = EventRegistration::with('event', 'user')->get();
        } elseif ($user->role === 'officer' && $user->club_id) {
            // Officers can only see registrations for their club's events
            $registrations = EventRegistration::with('event', 'user')
                ->whereHas('event', function($query) use ($user) {
                    $query->where('club_id', $user->club_id);
                })
                ->get();
        } else {
            // Students can only see their own registrations
            $registrations = EventRegistration::with('event', 'user')
                ->where('user_id', $user->id)
                ->get();
        }

        return view('registrations.index', compact('registrations'));
    }

    public function create()
    {
        $events = Event::all();
        $users = User::all();
        return view('registrations.create', compact('events', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:registered,attended',
        ]);

        // Check if already registered
        $existing = EventRegistration::where('event_id', $request->event_id)
            ->where('user_id', $request->user_id)
            ->first();
        if ($existing) {
            return back()->withErrors(['user_id' => 'User is already registered for this event.']);
        }

        EventRegistration::create([
            'event_id' => $request->event_id,
            'user_id' => $request->user_id,
            'ticket_code' => Str::random(10),
            'status' => $request->status,
        ]);

        return redirect()->route('registrations.index')->with('success', 'Registration created successfully.');
    }

    public function show(EventRegistration $registration)
    {
        $registration->load('event', 'user', 'attendanceLogs');
        return view('registrations.show', compact('registration'));
    }

    public function edit(EventRegistration $registration)
    {
        $events = Event::all();
        $users = User::all();
        return view('registrations.edit', compact('registration', 'events', 'users'));
    }

    public function update(Request $request, EventRegistration $registration)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:registered,attended',
        ]);

        $registration->update($request->only(['event_id', 'user_id', 'status']));

        return redirect()->route('registrations.index')->with('success', 'Registration updated successfully.');
    }

    public function destroy(EventRegistration $registration)
    {
        $registration->delete();
        return redirect()->route('registrations.index')->with('success', 'Registration deleted successfully.');
    }
}