<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VaccineStock;

class HCAdminController extends Controller
{
    public function dashboard()
    {
        // Fetch vaccine stocks data from the database
        $vaccineStocks = VaccineStock::all(); // Get all vaccine stock records

        // Return the view and pass the data
        return view('hcadmin.dashboard', compact('vaccineStocks'));
    }

   
    public function addStock(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'vaccineName' => 'required|string|max:255',
            'quantityToAdd' => 'required|integer|min:1', // Ensure the quantity is an integer and greater than 0
        ]);
    
        // Fetch the vaccine stock record based on the vaccine name
        $vaccine = VaccineStock::where('vaccine_name', $validated['vaccineName'])->first();
    
        if (!$vaccine) {
            return redirect()->back()->with('error', 'Vaccine not found.');
        }
    
        // Update the stock quantity
        $vaccine->current_quantity += $validated['quantityToAdd'];
    
        // Set the user who updated the stock
        $vaccine->last_updated_by = auth()->id();
    
        // Save the updated vaccine stock
        $vaccine->save();
    
        return redirect()->back()->with('success', 'Stock added successfully!');
    }
    

    public function addNewVaccine(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'vaccineName' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        // Create a new vaccine record
        VaccineStock::create([
            'vaccine_name' => $request->vaccineName,
            'current_quantity' => $request->quantity,
            'last_updated_by'  =>  auth()->id(),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'New vaccine added successfully!');
    }
    
    

}