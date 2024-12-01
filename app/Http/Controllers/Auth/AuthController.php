<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;  // Use Hash Facade
use App\Models\User;

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
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $user = Auth::user();

            // Redirect based on user role
            if ($user->role === 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'Patient') {
                return redirect()->route('patient.dashboard');
            } elseif ($user->role === 'HCAdmin') {
                return redirect()->route('hcadmin.dashboard');
            } elseif ($user->role === 'HCProvider') {
                return redirect()->route('hcprovider.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Incorrect credentials.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // Show Registration Form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Register User
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user with the validated data
        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'], // Optional, can be null
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),  // Use Hash to hash password
        ]);

        // Log the user in after successful registration
        Auth::login($user);

        return redirect()->route('patient.dashboard'); // Redirect to a specific route after successful registration
    }
}

