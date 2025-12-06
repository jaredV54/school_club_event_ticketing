<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use App\Models\PendingEventRegistration;
use App\Models\PendingUserAccount;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApprovalController extends Controller
{
    // Event Registration Approvals
    public function eventRegistrationsIndex(Request $request)
    {
        $user = auth()->user();

        $query = PendingEventRegistration::with('event', 'user')->where('status', 'pending');

        // Role-based filtering
        if ($user->role === 'officer') {
            // Officers can only see registrations for their club events
            $query->whereHas('event', function($q) use ($user) {
                $q->where('club_id', $user->club_id);
            });
        }
        // Admin can see all

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

        $pendingRegistrations = $query->orderBy('created_at', 'desc')->get();

        return view('approvals.event-registrations', compact('pendingRegistrations'));
    }

    public function approveEventRegistration(PendingEventRegistration $pendingRegistration)
    {
        $user = auth()->user();

        // Authorization check
        if ($user->role === 'officer' && $pendingRegistration->event->club_id !== $user->club_id) {
            abort(403, 'You can only approve registrations for your club events.');
        }

        // Check capacity
        $currentRegistrations = EventRegistration::where('event_id', $pendingRegistration->event_id)->count();
        if ($currentRegistrations >= $pendingRegistration->event->capacity) {
            return back()->withErrors(['capacity' => 'Event is at full capacity.']);
        }

        // Create approved registration
        EventRegistration::create([
            'event_id' => $pendingRegistration->event_id,
            'user_id' => $pendingRegistration->user_id,
            'ticket_code' => Str::random(10),
            'status' => 'registered',
        ]);

        // Update pending status
        $pendingRegistration->update(['status' => 'approved']);

        return redirect()->route('approvals.event-registrations.index')->with('success', 'Registration approved successfully.');
    }

    public function rejectEventRegistration(PendingEventRegistration $pendingRegistration)
    {
        $user = auth()->user();

        // Authorization check
        if ($user->role === 'officer' && $pendingRegistration->event->club_id !== $user->club_id) {
            abort(403, 'You can only reject registrations for your club events.');
        }

        $pendingRegistration->update(['status' => 'rejected']);

        return redirect()->route('approvals.event-registrations.index')->with('success', 'Registration rejected.');
    }

    // User Account Approvals (Admin only)
    public function userAccountsIndex(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            abort(403, 'Only admins can access user account approvals.');
        }

        $query = PendingUserAccount::with('club');

        // Apply filters
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $pendingAccounts = $query->orderBy('created_at', 'desc')->get();

        return view('approvals.user-accounts', compact('pendingAccounts'));
    }

    public function approveUserAccount(PendingUserAccount $pendingAccount)
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            abort(403, 'Only admins can approve user accounts.');
        }

        // Check if email already exists in users
        if (\App\Models\User::where('email', $pendingAccount->email)->exists()) {
            return back()->withErrors(['email' => 'A user with this email already exists.']);
        }

        // Create the user
        \App\Models\User::create([
            'name' => $pendingAccount->name,
            'email' => $pendingAccount->email,
            'password' => $pendingAccount->password,
            'club_id' => $pendingAccount->club_id,
            'role' => 'student',
        ]);

        // Delete the pending account
        $pendingAccount->delete();

        return redirect()->route('approvals.user-accounts.index')->with('success', 'User account approved successfully.');
    }

    public function rejectUserAccount(PendingUserAccount $pendingAccount)
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            abort(403, 'Only admins can reject user accounts.');
        }

        // Delete the pending account
        $pendingAccount->delete();

        return redirect()->route('approvals.user-accounts.index')->with('success', 'User account rejected.');
    }
}
