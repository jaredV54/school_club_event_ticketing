<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    public function search(Request $request)
    {
        $name = $request->query('name');
        $role = $request->query('role');

        if (!$name) {
            return response()->json([
                'success' => false,
                'message' => 'Name query is required'
            ]);
        }

        if (!auth()->check()) {
            return response()->json([
                'success' => true,
                'users' => []
            ]);
        }

        $query = User::where('name', 'like', '%' . $name . '%');

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->limit(10)->get(['id', 'name', 'email']);

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    public function suggestions(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Email query is required'
            ]);
        }

        // Only allow authenticated users to search
        if (!auth()->check()) {
            return response()->json([
                'success' => true,
                'suggestions' => []
            ]);
        }

        $query = User::where('email', 'like', '%' . $email . '%');

        $users = $query->limit(10)->get(['id', 'name', 'email', 'role']);

        $suggestions = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ];
        });

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions
        ]);
    }
}