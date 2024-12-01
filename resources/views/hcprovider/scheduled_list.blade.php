@extends('layouts.hcprovider-sidebar') 

@section('title', 'Scheduled Patient Details')

@section('content')

<h2>Find Patient</h2>

<form action="{{ route('hcprovider.find_scheduledpatient') }}" method="GET">
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lastName" class="form-label" style="font-weight: bold; margin-right: 115px; color: #286187;">Last Name</label>
                            <label for="firstName" class="form-label" style="font-weight: bold; margin-right: 115px; color: #286187;">First Name</label>
                            <label for="dob" class="form-label" style="font-weight: bold; margin-right: 40px; color: #286187;">Date of Birth</label>
                            <label for="middleName" class="form-label" style="font-weight: bold; color: #286187;">Middle Name</label>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" id="lastName" name="lastName" class="form-control mb-2" style="margin-right: 20px; height:22px;" placeholder="Enter Last Name">
                            <input type="text" id="firstName" name="firstName" class="form-control mb-2" style="margin-right: 20px; height:22px;" placeholder="Enter First Name">
                            <input type="date" id="dob" name="dob" class="form-control mb-2" style="margin-right: 20px; height:22px;">
                            <input type="text" id="middleName" name="middleName" class="form-control mb-2" style="margin-right: 30px; height:22px;" placeholder="Enter Middle Name">
                            <button type="submit" class="btn btn-primary" style="font-weight: bold; height:30px;">FIND</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="searched" value="true">
</form>

{{-- Table for Scheduled Patients --}}
@if(isset($scheduledPatientDetails) && $scheduledPatientDetails->count() > 0)
    <h3 class="mt-5" style="margin-top:30px; margin-bottom:3px;">Scheduled Patients Today</h3>
    <div class="container mt-1 mb-4">
        <table class="table table-bordered patient-table">
            <thead>
                <tr>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Vaccine</th>
                    <th>Dose Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scheduledPatientDetails as $patient)
                    <tr>
                        <td>{{ $patient->last_name }}</td>
                        <td>{{ $patient->first_name }}</td>
                        <td>{{ $patient->middle_name }}</td>
                        <td>{{ $patient->vaccine_name }}</td>
                        <td>{{ $patient->next_dose }}</td>  <!-- Display Next Dose -->
                        <td>
                            {{-- Check if there is a vaccination record with the patient_id and today's date in the administration_date --}}
                            @php
                                // Check if there is a record for this patient with today's administration_date
                                $vaccinationRecord = \App\Models\VaccinationRecord::where('patient_id', $patient->id)
                                    ->whereDate('administration_date', now()->format('Y-m-d'))
                                    ->first();
                            @endphp
                        
                            @if($vaccinationRecord)
                                <strong style="color: green;">DONE</strong>
                            @else
                                <form action="{{ route('hcprovider.selectPatient') }}" method="GET">
                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    <button type="submit" class="btn" 
                                            style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000;">
                                        <strong>ADMIT</strong>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    {{-- Show "No patient found" message --}}
    <div class="alert alert-warning mt-3" role="alert" style="margin-top:20px;">
        No patient found.
    </div>
@endif


@endsection
