<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $logs = AttendanceLog::with('registration.event', 'registration.user')->get();
        } elseif ($user->role === 'officer' && $user->club_id) {
            // Officers can only see attendance for their club's events
            $logs = AttendanceLog::with('registration.event', 'registration.user')
                ->whereHas('registration.event', function($query) use ($user) {
                    $query->where('club_id', $user->club_id);
                })
                ->get();
        } else {
            // Students can only see their own attendance
            $logs = AttendanceLog::with('registration.event', 'registration.user')
                ->whereHas('registration', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->get();
        }

        return view('attendance.index', compact('logs'));
    }

    public function create()
    {
        $registrations = EventRegistration::with('event', 'user')->get();
        return view('attendance.create', compact('registrations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:event_registrations,id',
            'timestamp' => 'required|date',
        ]);

        AttendanceLog::create($request->all());

        return redirect()->route('attendance.index')->with('success', 'Attendance log created successfully.');
    }

    public function show(AttendanceLog $attendance)
    {
        $attendance->load('registration.event', 'registration.user');
        return view('attendance.show', compact('attendance'));
    }

    public function edit(AttendanceLog $attendance)
    {
        $registrations = EventRegistration::with('event', 'user')->get();
        return view('attendance.edit', compact('attendance', 'registrations'));
    }

    public function update(Request $request, AttendanceLog $attendance)
    {
        $request->validate([
            'registration_id' => 'required|exists:event_registrations,id',
            'timestamp' => 'required|date',
        ]);

        $attendance->update($request->all());

        return redirect()->route('attendance.index')->with('success', 'Attendance log updated successfully.');
    }

    public function destroy(AttendanceLog $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendance.index')->with('success', 'Attendance log deleted successfully.');
    }
}