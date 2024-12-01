<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;



class AdminController extends Controller
{
    // Show the account creation form
    public function createAccountForm()
    {
        return view('admin.create-account');
    }

    // Handle the form submission to store the new account

    public function storeAccount(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'role' => 'required|string|in:HCProvider,HCAdmin', // Validate role
        'email' => 'required|email|unique:users,email',
    ]);

    // Create the user with the default password
    User::create([
        'first_name' => $request->input('first_name'), // Access input data
        'middle_name' => $request->input('middle_name'), // Access optional input data
        'last_name' => $request->input('last_name'), // Access input data
        'role' => $request->input('role'), // Access input role
        'email' => $request->input('email'), // Access input email
        'password' => Hash::make('defaultpassword'), // Hash the default password
    ]);

    // Redirect to the dashboard with a success message
    return redirect()->route('admin.dashboard')->with('success', 'Account created successfully!');
}

    
    
}
