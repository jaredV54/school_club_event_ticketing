<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = AttendanceLog::with('registration.event', 'registration.user');

        // Apply role-based filtering
        if ($user->role === 'officer' && $user->club_id) {
            // Officers can only see attendance for their club's events
            $query->whereHas('registration.event', function($q) use ($user) {
                $q->where('club_id', $user->club_id);
            });
        } elseif ($user->role === 'student') {
            // Students can only see their own attendance
            $query->whereHas('registration', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Apply filters
        if ($request->filled('event_title')) {
            $query->whereHas('registration.event', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->event_title . '%');
            });
        }

        if ($request->filled('student_name')) {
            $query->whereHas('registration.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->student_name . '%');
            });
        }

        if ($request->filled('student_email')) {
            $query->whereHas('registration.user', function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->student_email . '%');
            });
        }

        if ($request->filled('ticket_code')) {
            $query->whereHas('registration', function($q) use ($request) {
                $q->where('ticket_code', 'like', '%' . $request->ticket_code . '%');
            });
        }

        if ($request->filled('timestamp_from')) {
            $query->where('timestamp', '>=', $request->timestamp_from . ' 00:00:00');
        }

        if ($request->filled('timestamp_to')) {
            $query->where('timestamp', '<=', $request->timestamp_to . ' 23:59:59');
        }

        $logs = $query->get();

        return view('attendance.index', compact('logs'));
    }

    public function create()
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
            abort(403, 'Unauthorized access.');
        }
        
        return view('attendance.create', compact('registrations'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'registration_id' => 'required|exists:event_registrations,id',
            'timestamp' => 'required|date',
        ]);

        // Verify officer can only mark attendance for their club's events
        if ($user->role === 'officer' && $user->club_id) {
            $registration = EventRegistration::with('event')->findOrFail($request->registration_id);
            if ($registration->event->club_id !== $user->club_id) {
                abort(403, 'You can only mark attendance for your club\'s events.');
            }
        }

        AttendanceLog::create($request->all());

        return redirect()->route('attendance.index')->with('success', 'Attendance log created successfully.');
    }

    public function show(AttendanceLog $attendance)
    {
        $user = auth()->user();
        
        // Authorization check
        if ($user->role === 'officer' && $user->club_id) {
            if ($attendance->registration->event->club_id !== $user->club_id) {
                abort(403, 'You can only view attendance for your club\'s events.');
            }
        } elseif ($user->role === 'student') {
            if ($attendance->registration->user_id !== $user->id) {
                abort(403, 'You can only view your own attendance.');
            }
        }
        
        $attendance->load('registration.event', 'registration.user');
        return view('attendance.show', compact('attendance'));
    }

    public function edit(AttendanceLog $attendance)
    {
        $user = auth()->user();
        
        // Authorization check
        if ($user->role === 'officer' && $user->club_id) {
            if ($attendance->registration->event->club_id !== $user->club_id) {
                abort(403, 'You can only edit attendance for your club\'s events.');
            }
        }
        
        if ($user->role === 'admin') {
            $registrations = EventRegistration::with('event', 'user')->get();
        } elseif ($user->role === 'officer' && $user->club_id) {
            $registrations = EventRegistration::with('event', 'user')
                ->whereHas('event', function($query) use ($user) {
                    $query->where('club_id', $user->club_id);
                })
                ->get();
        } else {
            abort(403, 'Unauthorized access.');
        }
        
        return view('attendance.edit', compact('attendance', 'registrations'));
    }

    public function update(Request $request, AttendanceLog $attendance)
    {
        $user = auth()->user();
        
        $request->validate([
            'registration_id' => 'required|exists:event_registrations,id',
            'timestamp' => 'required|date',
        ]);

        // Authorization check
        if ($user->role === 'officer' && $user->club_id) {
            if ($attendance->registration->event->club_id !== $user->club_id) {
                abort(403, 'You can only update attendance for your club\'s events.');
            }
            
            // Verify new registration is also from their club
            $newRegistration = EventRegistration::with('event')->findOrFail($request->registration_id);
            if ($newRegistration->event->club_id !== $user->club_id) {
                abort(403, 'You can only update to registrations from your club\'s events.');
            }
        }

        $attendance->update($request->all());

        return redirect()->route('attendance.index')->with('success', 'Attendance log updated successfully.');
    }

    public function destroy(AttendanceLog $attendance)
    {
        $user = auth()->user();
        
        // Authorization check
        if ($user->role === 'officer' && $user->club_id) {
            if ($attendance->registration->event->club_id !== $user->club_id) {
                abort(403, 'You can only delete attendance for your club\'s events.');
            }
        }
        
        $attendance->delete();
        return redirect()->route('attendance.index')->with('success', 'Attendance log deleted successfully.');
    }
}