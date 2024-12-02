<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\VaccinationRecord;
use Illuminate\Http\Request;
use App\Models\VaccineStock; // Make sure this is the correct namespace for your model
use Illuminate\Support\Facades\Session;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Hash;


class PatientController extends Controller
{
  
    public function dashboard()
    {
        $email = Auth::user()->email;
    
        // Find the patient using the email
        $patient = Patient::where('email', $email)->first();
    
        if (!$patient) {
            // Pass null or an appropriate message to the view if no patient is found
            return view('patient.dashboard', [
                'records' => collect(), // Empty collection to avoid errors
                'patientDetails' => null, // No patient details
                'patientNotFound' => true // Flag to indicate patient record not found
            ]);
        }
    
        // Fetch vaccination records along with user details
        $records = VaccinationRecord::where('patient_id', $patient->id)
            ->leftJoin('users', 'vaccination_records.administered_by', '=', 'users.id')
            ->select(
                'vaccination_records.vaccine_name',
                'vaccination_records.dose_number',
                'vaccination_records.administration_date',
                'users.first_name',
                'users.middle_name',
                'users.last_name'
            )
            ->get();
    
        return view('patient.dashboard', [
            'records' => $records,
            'patientDetails' => $patient, // Pass patient details to the view
            'patientNotFound' => false // No error, patient exists
        ]); 
    }
    
    public function viewSchedule()
{
    $email = Auth::user()->email;

    // Find the patient using the email
    $patient = Patient::where('email', $email)->first();

    if (!$patient) {
        // If no patient record is found, return a view with an appropriate message
        return view('patient.schedule', [
            'schedules' => collect(), // Empty collection
            'patientNotFound' => true // Indicate no patient record
        ]);
    }

    // Fetch upcoming schedules from the vaccination records
    $schedules = VaccinationRecord::where('patient_id', $patient->id)
        ->whereDate('scheduled_date', '>=', Carbon::today()) // Only future or today’s schedules
        ->select(
            'vaccine_name',
            'scheduled_date',
            'dose_number'
        )
        ->get()
        ->map(function ($record) {
            // Update the dose_number for "Next Dose"
            switch ($record->dose_number) {
                case 'First':
                    $record->next_dose = 'Second';
                    break;
                case 'Second':
                    $record->next_dose = 'Third';
                    break;
                case 'Third':
                    $record->next_dose = 'Booster';
                    break;
                default:
                    $record->next_dose = 'Not Applicable';
            }
            return $record;
        });

    return view('patient.schedule', [
        'schedules' => $schedules, // Pass the modified schedule data
        'patientNotFound' => false // Indicate patient exists
    ]);
}

public function accountSettings()
{
    // Assuming the logged-in user information is available via Auth
    $user = auth()->user();

    // Pass the user details to the blade view
    return view('patient.account_settings', compact('user'));
}

public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|confirmed|min:8',
    ]);

    $user = auth()->user();

    // Check if the current password matches
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    // Update the password
    $user->update([
        'password' => Hash::make($request->new_password),
    ]);

    // Flash a success message
    return redirect()->route('patient.dashboard')->with('success', 'Password successfully changed.');
}
    
    
}
