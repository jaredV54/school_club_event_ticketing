<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PendingUserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Check if email is in pending accounts
        $pendingAccount = PendingUserAccount::where('email', $request->email)->first();
        if ($pendingAccount) {
            return back()->withErrors([
                'email' => 'Your account is pending approval by an administrator.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegisterForm()
    {
        $clubs = \App\Models\Club::all();
        return view('auth.register', compact('clubs'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'club_id' => 'required|exists:clubs,id',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
                'confirmed'
            ],
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'club_id.required' => 'Please select a club.',
            'club_id.exists' => 'Selected club does not exist.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Check if email already exists in users or pending accounts
        if (User::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'This email address is already registered.']);
        }

        if (PendingUserAccount::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'This email address is already pending approval.']);
        }

        try {
            // Create pending account
            PendingUserAccount::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'club_id' => $request->club_id,
                'status' => 'pending',
            ]);

            return back()->with('success', 'Your registration request has been sent. Please wait for approval by an administrator.');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'An error occurred while submitting your account. Please try again.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}