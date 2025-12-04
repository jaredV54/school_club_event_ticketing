<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = EventRegistration::with('event', 'user');

        // Apply role-based filtering
        if ($user->role === 'officer' && $user->club_id) {
            // Officers can only see registrations for their club's events
            $query->whereHas('event', function($q) use ($user) {
                $q->where('club_id', $user->club_id);
            });
        } elseif ($user->role === 'student') {
            // Students can only see their own registrations
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->filled('event_title')) {
            $query->whereHas('event', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->event_title . '%');
            });
        }

        if ($request->filled('student_name')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->student_name . '%');
            });
        }

        if ($request->filled('student_email')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->student_email . '%');
            });
        }

        if ($request->filled('ticket_code')) {
            $query->where('ticket_code', 'like', '%' . $request->ticket_code . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('registered_from')) {
            $query->where('created_at', '>=', $request->registered_from . ' 00:00:00');
        }

        if ($request->filled('registered_to')) {
            $query->where('created_at', '<=', $request->registered_to . ' 23:59:59');
        }

        $registrations = $query->get();

        return view('registrations.index', compact('registrations'));
    }

    public function create()
    {
        $user = auth()->user();
        $events = Event::all();
        
        if ($user->role === 'admin') {
            $users = User::all();
        } else {
            // Students only see themselves
            $users = User::where('id', $user->id)->get();
        }
        
        return view('registrations.create', compact('events', 'users'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:registered,attended',
        ]);

        // Students can only register themselves
        if ($user->role === 'student' && $request->user_id != $user->id) {
            abort(403, 'You can only register yourself for events.');
        }

        // Check event capacity
        $event = Event::findOrFail($request->event_id);
        $currentRegistrations = EventRegistration::where('event_id', $event->id)->count();

        if ($currentRegistrations >= $event->capacity) {
            return back()->withErrors(['event_id' => 'This event is at full capacity.']);
        }

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
        $user = auth()->user();
        
        // Authorization check
        if ($user->role === 'officer' && $user->club_id) {
            if ($registration->event->club_id !== $user->club_id) {
                abort(403, 'You can only view registrations for your club\'s events.');
            }
        } elseif ($user->role === 'student') {
            if ($registration->user_id !== $user->id) {
                abort(403, 'You can only view your own registrations.');
            }
        }
        
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