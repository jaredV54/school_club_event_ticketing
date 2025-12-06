<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class RegistrationApiController extends Controller
{
    public function search(Request $request)
    {
        $ticketCode = $request->query('ticket_code');

        if (!$ticketCode) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket code is required'
            ]);
        }

        $registration = EventRegistration::with('event', 'user')
            ->where('ticket_code', $ticketCode)
            ->where('status', 'registered')
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => true,
                'suggestions' => []
            ]);
        }

        // Check if user has permission to view this registration
        $user = auth()->user();
        if ($user && $user->role) {
            if ($user->role === 'officer' && $user->club_id) {
                if ($registration->event->club_id !== $user->club_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to view this registration'
                    ]);
                }
            } elseif ($user->role === 'student') {
                if ($registration->user_id !== $user->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You can only search for your own registrations'
                    ]);
                }
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ]);
        }

        return response()->json([
            'success' => true,
            'registration' => [
                'id' => $registration->id,
                'event_title' => $registration->event->title,
                'student_name' => $registration->user->name,
                'student_email' => $registration->user->email,
                'status' => $registration->status,
                'registered_at' => $registration->created_at->format('M d, Y H:i'),
                'ticket_code' => $registration->ticket_code
            ]
        ]);
    }

    public function suggestions(Request $request)
    {
        $ticketCode = $request->query('ticket_code');

        if (!$ticketCode) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket code query is required'
            ]);
        }

        $query = EventRegistration::with('event', 'user')
            ->where('ticket_code', 'like', '%' . $ticketCode . '%')
            ->where('status', 'registered'); // Only show registered students, not attended

        // Apply role-based filtering
        $user = auth()->user();
        if ($user && $user->role) {
            if ($user->role === 'officer' && $user->club_id) {
                $query->whereHas('event', function($q) use ($user) {
                    $q->where('club_id', $user->club_id);
                });
            } elseif ($user->role === 'student') {
                $query->where('user_id', $user->id);
            }
        } else {
            // If not authenticated or invalid user, return no results
            return response()->json([
                'success' => true,
                'suggestions' => []
            ]);
        }

        $registrations = $query->limit(10)->get([
            'id', 'ticket_code', 'event_id', 'user_id', 'status', 'created_at'
        ]);

        $suggestions = $registrations->map(function($registration) {
            return [
                'id' => $registration->id,
                'ticket_code' => $registration->ticket_code,
                'event_title' => $registration->event->title,
                'student_name' => $registration->user->name,
                'student_email' => $registration->user->email,
                'student_role' => $registration->user->role,
                'status' => $registration->status,
                'registered_at' => $registration->created_at->format('M d, Y H:i')
            ];
        });

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions
        ]);
    }
}