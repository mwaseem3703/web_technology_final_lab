<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Show the dedicated Admin Login Form
     */
    public function showLoginForm()
    {
        // If an admin is already logged in, send them straight to the dashboard
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    /**
     * Process the Admin Login Attempt
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'], // We use 'email' field to pass 'admin'
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if the user that just logged in is actually an admin
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            } else {
                // If a normal student tries to use this portal, log them out
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Access Denied. You do not have administrative privileges.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our system records.',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Admin safely logged out.');
    }
}