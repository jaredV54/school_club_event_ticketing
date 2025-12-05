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
        if ($user->role === 'student') {
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

        $logs = $query->orderBy('created_at', 'desc')->get();

        return view('attendance.index', compact('logs'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->role === 'admin' || $user->role === 'officer') {
            $registrations = EventRegistration::with('event', 'user')->get();
        } else {
            abort(403, 'Unauthorized access.');
        }
        
        return view('attendance.create', compact('registrations'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'ticket_code' => 'required|string',
            'registration_id' => 'required|exists:event_registrations,id',
            'timestamp' => 'required|date',
        ]);

        // Find the registration by ticket code to verify it exists
        $registration = EventRegistration::with('event')->findOrFail($request->registration_id);

        // Verify the ticket code matches
        if ($registration->ticket_code !== $request->ticket_code) {
            return back()->withErrors(['ticket_code' => 'Ticket code does not match the selected registration.']);
        }

        // Officers have full access like admin

        // Check if attendance has already been logged for this registration
        $existingAttendance = AttendanceLog::where('registration_id', $request->registration_id)->first();
        if ($existingAttendance) {
            return back()->withErrors(['ticket_code' => 'Attendance has already been logged for this registration.']);
        }

        // Create the attendance log
        AttendanceLog::create([
            'registration_id' => $request->registration_id,
            'timestamp' => $request->timestamp,
        ]);

        // Update the registration status to "attended"
        $registration->update(['status' => 'attended']);

        return redirect()->route('attendance.index')->with('success', 'Attendance log created successfully.');
    }

    public function show(AttendanceLog $attendance)
    {
        $user = auth()->user();
        
        // Authorization check
        if ($user->role === 'student') {
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
        
        if ($user->role === 'admin' || $user->role === 'officer') {
            $registrations = EventRegistration::with('event', 'user')->get();
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

        // Officers have full access like admin

        // Check if attendance has already been logged for the new registration (if different)
        if ($request->registration_id != $attendance->registration_id) {
            $existingAttendance = AttendanceLog::where('registration_id', $request->registration_id)->first();
            if ($existingAttendance) {
                return back()->withErrors(['registration_id' => 'Attendance has already been logged for this registration.']);
            }

            // Revert old registration status
            $attendance->registration->update(['status' => 'registered']);

            // Update new registration status
            $newRegistration = EventRegistration::findOrFail($request->registration_id);
            $newRegistration->update(['status' => 'attended']);
        }

        $attendance->update($request->all());

        return redirect()->route('attendance.index')->with('success', 'Attendance log updated successfully.');
    }

    public function destroy(AttendanceLog $attendance)
    {
        $user = auth()->user();
        
        // Officers have full access like admin

        // Revert the registration status back to "registered"
        $attendance->registration->update(['status' => 'registered']);

        $attendance->delete();
        return redirect()->route('attendance.index')->with('success', 'Attendance log deleted successfully.');
    }
}