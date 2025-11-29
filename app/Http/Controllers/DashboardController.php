<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Club;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $stats = [];

        if ($user->role === 'admin') {
            // Admin sees all statistics
            $stats = [
                'total_users' => User::count(),
                'total_clubs' => Club::count(),
                'total_events' => Event::count(),
                'total_registrations' => EventRegistration::count(),
                'total_attendance' => AttendanceLog::count(),
            ];

            $recent_events = Event::with('club')->latest()->take(10)->get();
            $recent_registrations = EventRegistration::with('event', 'user')->latest()->take(10)->get();

        } elseif ($user->role === 'officer' && $user->club_id) {
            $stats = [
                'club_events' => Event::where('club_id', $user->club_id)->count(),
                'club_registrations' => EventRegistration::whereHas('event', function($query) use ($user) {
                    $query->where('club_id', $user->club_id);
                })->count(),
                'club_attendance' => AttendanceLog::whereHas('registration.event', function($query) use ($user) {
                    $query->where('club_id', $user->club_id);
                })->count(),
            ];

            $recent_events = Event::with('club')->where('club_id', $user->club_id)->latest()->take(10)->get();
            $recent_registrations = EventRegistration::with('event', 'user')
                ->whereHas('event', function($query) use ($user) {
                    $query->where('club_id', $user->club_id);
                })
                ->latest()->take(10)->get();

        } else {
            // Student sees personal statistics
            $stats = [
                'my_registrations' => EventRegistration::where('user_id', $user->id)->count(),
                'my_attendance' => AttendanceLog::whereHas('registration', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count(),
            ];

            $recent_events = Event::with('club')->latest()->take(10)->get();
            $recent_registrations = EventRegistration::with('event', 'user')
                ->where('user_id', $user->id)
                ->latest()->take(10)->get();
        }

        return view('dashboard.index', compact('stats', 'recent_events', 'recent_registrations'));
    }
}