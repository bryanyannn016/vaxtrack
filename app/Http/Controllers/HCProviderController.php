<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\VaccinationRecord;
use Illuminate\Http\Request;
use App\Models\VaccineStock; // Make sure this is the correct namespace for your model
use Illuminate\Support\Facades\Session;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Hash;



class HCProviderController extends Controller
{
    /**
     * Find patients based on search criteria.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function findPatient(Request $request)
    {
        $patients = Patient::query();

        // Add filters for each search parameter
        if ($request->has('lastName') && $request->lastName) {
            $patients->where('last_name', 'LIKE', '%' . $request->lastName . '%');
        }
        if ($request->has('firstName') && $request->firstName) {
            $patients->where('first_name', 'LIKE', '%' . $request->firstName . '%');
        }
        if ($request->has('dob') && $request->dob) {
            $patients->where('birthday', $request->dob);
        }
        if ($request->has('middleName') && $request->middleName) {
            $patients->where('middle_name', 'LIKE', '%' . $request->middleName . '%');
        }

        // Execute query
        $patients = $patients->get();

        return view('hcprovider.dashboard', compact('patients'));
    }

    public function index()
{
   
    // Retrieve all patients
    $patients = Patient::all();

    // Pass only eligible patients to the view
    return view('hcprovider.dashboard', compact('patients'));
}

    /**
     * Show the create patient form.
     *
     * @return \Illuminate\View\View
     */
    public function showCreatePatientForm()
    {
        return view('hcprovider.create_patient');
    }

    
    
    public function savePatient(Request $request)
{
    // Validate the incoming request
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'birthday' => 'required|date',
        'sex' => 'required|string',
        'email' => 'required|email|max:255',
        'address' => 'required|string|max:255',
        'medical_history' => 'nullable|string|max:1000', // Optional field
    ]);

    // Set middle_name to null if 'no_middle_name' checkbox is checked
    if ($request->has('no_middle_name')) {
        $validatedData['middle_name'] = null;
    }
   

    // Store the patient record in the session or database
    session(['patient_data' => $validatedData]);

    // Redirect to a success page or back with a success message
    return redirect()->route('hcprovider.admitPatient')->with('success', 'Patient record created successfully!');
}



public function admitPatient()
{
    $patient = session('patient_data');
    // Fetch only vaccines with current_quantity > 0
    $vaccineStocks = VaccineStock::where('current_quantity', '>', 0)->get();
    return view('hcprovider.admit_patient', compact('patient', 'vaccineStocks'));
}

public function storeAdmitPatient(Request $request)
{
    // Validate incoming request
    $request->validate([
        'vaccine_name' => 'required|integer|exists:vaccine_stocks,stock_id', // Ensure vaccine_name corresponds to a valid ID in vaccine_stocks
        'dose_number' => 'required|string|in:First,Second,Third,Booster', // Validate dose number is one of the predefined strings
        'next_dose_schedule' => 'nullable|date|after_or_equal:tomorrow',
    ]);

    // Retrieve patient data from session
    $patientData = session('patient_data');

    // Ensure patient data is available
    if (!$patientData || !isset($patientData['birthday'])) {
        return redirect()->back()->withErrors(['patient_data' => 'Patient data is missing or incomplete.']);
    }

    // Retrieve and validate the birthday from patient data
    try {
        $birthday = Carbon::createFromFormat('Y-m-d', $patientData['birthday']);
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['birthday' => 'Invalid date format in patient data.']);
    }

    // Calculate age
    $age = $birthday->diffInYears(Carbon::now());

    // Create a new Patient record
    $patient = new Patient();
    $patient->first_name = $patientData['first_name'];
    $patient->middle_name = $patientData['middle_name'];
    $patient->last_name = $patientData['last_name'];
    $patient->birthday = $patientData['birthday']; // Store the valid birthday
    $patient->sex = $patientData['sex'];
    $patient->address = $patientData['address'];
    $patient->age = $age; // Use the calculated age
    $patient->medical_history = $patientData['medical_history'];
    $patient->email = $patientData['email'];
    $patient->created_by = auth()->id();
    $patient->save();

    // Find the selected vaccine stock
    $vaccineStock = VaccineStock::find($request->vaccine_name);

    if (!$vaccineStock) {
        return redirect()->back()->withErrors(['vaccine_name' => 'Selected vaccine is not available.']);
    }

    // Ensure there's enough stock for the requested dose
    if ($vaccineStock->current_quantity < 1) { // Assume 1 dose per request
        return redirect()->back()->withErrors(['dose_number' => 'Not enough stock available for the selected dose.']);
    }

    // Subtract 1 from the current_quantity for the dose
    $vaccineStock->current_quantity -= 1;
    $vaccineStock->save();

    // Create a new VaccinationRecord
    $vaccinationRecord = new VaccinationRecord();
    $vaccinationRecord->patient_id = $patient->id; // Link to the patient
    $vaccinationRecord->vaccine_name = $vaccineStock->vaccine_name; // Use the vaccine name from the stock
    $vaccinationRecord->dose_number = $request->dose_number; // Store the selected dose name (First, Second, etc.)
    $vaccinationRecord->administration_date = now(); // Set current date
    $vaccinationRecord->administered_by = auth()->id(); // Administered by the logged-in user
    $vaccinationRecord->scheduled_date = $request->next_dose_schedule; // Nullable
    $vaccinationRecord->status = 'Completed';
    $vaccinationRecord->save();

    // Clear the session data after saving
    Session::forget('patient_data');

    // Redirect with success message
    return redirect()->route('hcprovider.dashboard')->with('success', 'Patient admitted successfully!');
}


public function viewPatientRecord($id)
{
    // Retrieve the patient by ID
    $patient = Patient::findOrFail($id); 

    // Retrieve all records associated with the patient
    $records = VaccinationRecord::where('patient_id', $id)->get();

    // Return a view and pass the patient and records data
    return view('hcprovider.view_patient_record', compact('patient', 'records'));
}


public function viewExistingPatientRecord($patient_id, $vaccination_record_id)
{
    // Fetch patient details
    $patient = Patient::findOrFail($patient_id);

    // Fetch record details with the administeredBy relationship
    $vaccinationrecord = VaccinationRecord::with('administeredBy')->findOrFail($vaccination_record_id);

    // Pass data to the view
    return view('hcprovider.patient_record_view', compact('patient', 'vaccinationrecord'));
}

public function selectPatient(Request $request)
{
    // Validate the patient ID
    $validatedData = $request->validate([
        'patient_id' => 'required|exists:patients,id',
    ]);

    // Retrieve the patient by ID
    $patient = Patient::findOrFail($validatedData['patient_id']);

    // Concatenate the name in the format: "Last Name, First Name Middle Name"
    $fullName = $patient->first_name . ' ' . $patient->middle_name . ' ' . $patient->last_name;

    // Retrieve all available vaccine stocks (assuming there's a VaccineStock model)
    $vaccineStocks = VaccineStock::all(); // Adjust this to your actual model/query as needed

    // Store the relevant data in the session, including the patient ID
    session([
        'selected_patient' => [
            'id' => $patient->id, // Store the patient ID
            'full_name' => $fullName,
            'sex' => $patient->sex,
            'birthday' => $patient->birthday,
            'email' => $patient->email,
            'address' => $patient->address,
        ]
    ]);

    // Redirect to the existing_patient view and pass the patient's data
    return view('hcprovider.existing_patient', [
        'patient' => $patient,
        'vaccineStocks' => $vaccineStocks, // Pass vaccine stocks to the view
    ]);
}


public function storeExistingPatient(Request $request)
{
    // Log the session data for debugging
    \Log::info('Session Data:', session('selected_patient'));

    // Validate incoming request
    $request->validate([
        'vaccine_name' => 'required|integer|exists:vaccine_stocks,stock_id', // Ensure vaccine_name corresponds to a valid ID in vaccine_stocks
        'dose_number' => 'required|string|in:First,Second,Third,Booster', // Validate dose number is one of the predefined strings
        'next_dose_schedule' => 'nullable|date|after_or_equal:tomorrow',
    ]);

    // Retrieve patient data from session
    $patientData = session('selected_patient');


    // Retrieve patient ID from the session
    $patientId = $patientData['id'];

    // Ensure the patient exists in the database
    $existingPatient = Patient::find($patientId);
    if (!$existingPatient) {
        return redirect()->back()->withErrors(['patient_data' => 'Selected patient not found.']);
    }

    $vaccineStock = VaccineStock::find($request->vaccine_name);


    if (!$vaccineStock) {
        return redirect()->back()->withErrors(['vaccine_name' => 'Selected vaccine is not available.']);
    }

 

      // Subtract 1 from the current_quantity since only 1 dose is administered at a time
      $vaccineStock->current_quantity -= 1;
      $vaccineStock->save();

    // Create a new VaccinationRecord
    $vaccinationRecord = new VaccinationRecord();
    $vaccinationRecord->patient_id = $existingPatient->id; // Link to the patient
    $vaccinationRecord->vaccine_name = $vaccineStock->vaccine_name; // Use the vaccine name from the stock
    $vaccinationRecord->dose_number = $request->dose_number; // Requested dose number
    $vaccinationRecord->administration_date = now(); // Set current date
    $vaccinationRecord->administered_by = auth()->id(); // Administered by the logged-in user
    $vaccinationRecord->scheduled_date = $request->next_dose_schedule; // Nullable
    $vaccinationRecord->status = 'Completed';
    $vaccinationRecord->save();


    // Clear the session data after saving
    Session::forget('selected_patient');

    // Redirect with success message
    return redirect()->route('hcprovider.dashboard')->with('success', 'Record saved for existing patient successfully!');
}


public function ScheduledPatient(Request $request)
{
    // Get today's date
    $today = now()->format('Y-m-d');

    // Query VaccinationRecord model for today's scheduled_date and join with Patient table
    $scheduledPatientDetails = VaccinationRecord::where('scheduled_date', $today)
        ->join('patients', 'vaccination_records.patient_id', '=', 'patients.id')
        ->select('patients.*', 'vaccination_records.vaccine_name', 'vaccination_records.administration_date', 'vaccination_records.patient_id', 'vaccination_records.dose_number')
        ->get();

    // Process the dose number to display the next dose
    foreach ($scheduledPatientDetails as $patient) {
        // Determine next dose
        if ($patient->dose_number == 'First') {
            $patient->next_dose = 'Second';
        } elseif ($patient->dose_number == 'Second') {
            $patient->next_dose = 'Third';
        } elseif ($patient->dose_number == 'Third') {
            $patient->next_dose = 'Booster';
        } else {
            $patient->next_dose = 'N/A'; // In case no valid dose found
        }
    }

    // Pass the scheduled patients' details to the view
    return view('hcprovider.scheduled_list', compact('scheduledPatientDetails', 'today'));
}




public function findScheduledPatient(Request $request)
{
    // Validate the input
    $request->validate([
        'lastName' => 'nullable|string|max:255',
        'firstName' => 'nullable|string|max:255',
        'dob' => 'nullable|date',
        'middleName' => 'nullable|string|max:255',
    ]);

    // Get today's date
    $today = now()->format('Y-m-d');

    // Build the query for scheduled patients with vaccine details
    $query = VaccinationRecord::where('scheduled_date', $today)
        ->join('patients', 'vaccination_records.patient_id', '=', 'patients.id')
        ->select('patients.*', 'vaccination_records.vaccine_name');

    // Apply filters based on search criteria
    if ($request->filled('lastName')) {
        $query->where('patients.last_name', 'like', '%' . $request->lastName . '%');
    }
    if ($request->filled('firstName')) {
        $query->where('patients.first_name', 'like', '%' . $request->firstName . '%');
    }
    if ($request->filled('dob')) {
        $query->where('patients.dob', $request->dob);
    }
    if ($request->filled('middleName')) {
        $query->where('patients.middle_name', 'like', '%' . $request->middleName . '%');
    }

    // Execute the query and get the filtered results
    $scheduledPatientDetails = $query->get();

    // Pass the scheduled patients' details to the view
    return view('hcprovider.scheduled_list', compact('scheduledPatientDetails'));
}


public function accountSettings()
{
    // Assuming the logged-in user information is available via Auth
    $user = auth()->user();

    // Pass the user details to the blade view
    return view('hcprovider.account_settings', compact('user'));
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
    return redirect()->route('hcprovider.dashboard')->with('success', 'Password successfully changed.');
}
    
    
}
