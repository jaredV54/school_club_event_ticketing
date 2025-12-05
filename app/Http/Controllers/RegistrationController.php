<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use App\Models\Event;
use App\Models\User;
use App\Models\PendingEventRegistration;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = EventRegistration::with('event', 'user');

        // Apply role-based filtering
        if ($user->role === 'student') {
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

        $registrations = $query->orderBy('created_at', 'desc')->get();

        return view('registrations.index', compact('registrations'));
    }

    public function create()
    {
        $user = auth()->user();
        $events = Event::all();

        return view('registrations.create', compact('events'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
        ];

        if ($user->role !== 'student') {
            $rules['status'] = 'required|in:registered,attended';
        }

        $request->validate($rules);

        // Students can only register themselves
        if ($user->role === 'student' && $request->user_id != $user->id) {
            abort(403, 'You can only register yourself for events.');
        }

        // Check for duplicate registration (across approved registrations and attendance)
        $this->validateNoDuplicateRegistration($request->event_id, $request->user_id);

        // Check event capacity (only for approved registrations)
        $event = Event::findOrFail($request->event_id);
        $currentApprovedRegistrations = EventRegistration::where('event_id', $event->id)->count();

        if ($currentApprovedRegistrations >= $event->capacity) {
            return back()->withErrors(['event_id' => 'This event is at full capacity.']);
        }

        // Handle registration based on user role and selected status
        if ($user->role === 'student') {
            // Students always require approval
            PendingEventRegistration::create([
                'event_id' => $request->event_id,
                'user_id' => $request->user_id,
                'role' => $user->role,
                'status' => 'pending',
            ]);

            return redirect()->route('registrations.index')->with('success', 'Registration request submitted for approval.');
        } else {
            // Admins/Officers can create approved registrations directly
            EventRegistration::create([
                'event_id' => $request->event_id,
                'user_id' => $request->user_id,
                'ticket_code' => Str::random(10),
                'status' => $request->status,
            ]);

            return redirect()->route('registrations.index')->with('success', 'Registration created successfully.');
        }
    }

    public function show(EventRegistration $registration)
    {
        $user = auth()->user();
        
        // Authorization check
        if ($user->role === 'student') {
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

    private function validateNoDuplicateRegistration($eventId, $userId)
    {
        // Check approved registrations
        $existingRegistration = EventRegistration::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->first();

        if ($existingRegistration) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'user_id' => 'User is already registered for this event.'
            ]);
        }

        // Check attendance logs (which indicate past attendance)
        $existingAttendance = AttendanceLog::whereHas('registration', function($query) use ($eventId, $userId) {
            $query->where('event_id', $eventId)
                  ->where('user_id', $userId);
        })->first();

        if ($existingAttendance) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'user_id' => 'User has already attended this event.'
            ]);
        }
    }
}