@extends('layouts.hcprovider-sidebar') 

@section('title', 'Patient Records')

@section('content')

<style>
    .custom-table {
        margin-top: 20px; /* Gap below patient information */
        width: 100%; /* Adjusted width to make the table smaller */
        height: 20%;
        border-collapse: collapse; /* Ensures borders collapse for a cleaner look */
        margin-right: auto; /* Center the table */
        margin-left:200px;
    }

    .custom-table th, .custom-table td {
        border: 1px solid #dee2e6; /* Border color */
        padding: 2px; /* Reduced padding for smaller cells */
        text-align: center; /* Center align text in cells */
        font-size: 16px; /* Reduced font size for smaller cells */
    }

    .custom-table th {
        background-color: #C6E0FF; /* Updated header background color */
        font-weight: bold; /* Bold text for headers */
    }

    .custom-table tr:nth-child(even) {
        background-color: #ffffff; /* Alternate row background color */
    }

    .custom-margin-right {
        margin-right: 150px; /* Adjust this value to set the desired margin */
    }

    .button-container {
        margin-top: 100px; /* Space above the button */
        text-align: left; /* Align button to the left */
        margin-left: 70px;
    }

    .button-container .btn-primary{
        border-color: #C6E0FF;
        background-color: #C6E0FF;
    }

    .btn-info {
        background-color: #C6E0FF; /* Updated button background color */
        border-color: #C6E0FF; /* Updated button border color */
    }

    .btn-info:hover {
        background-color: #A4C8E1; /* Optional: Slightly darker on hover */
        border-color: #A4C8E1; /* Optional: Slightly darker on hover */
    }

    .label-color {
        color: #286187; /* Updated label color */
    }
</style>

<div class="container mt-4">

    <div class="row mb-3">
        <div class="col-md-6 custom-margin-right"> <!-- Applied custom margin class -->
            <p><strong class="label-color">Name:</strong> {{ $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}</p>
        </div>
        <div class="col-md-6 custom-margin-right"> <!-- Applied custom margin class -->
            <p><strong class="label-color">Sex:</strong> {{ $patient->sex }}</p>
        </div>
        
        <h3 style="margin-top:50px; margin-left:350px; color: #286187;"> Vaccination Records: </h3>
        @if($records->isEmpty())
            <p>No records found for this patient.</p>
        @else
            <table class="table table-bordered custom-table"> <!-- Removed margin right here -->
                <thead>
                    <tr>
                        <th>Vaccine</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $vaccinerecord)
                        <tr>
                            <td style="font-size:14px;">{{ $vaccinerecord->vaccine_name }}</td>
                            <td style="font-size:14px;">{{ $vaccinerecord->administration_date }}</td>
                            <td>
                    
                                <form action="{{ route('hcprovider.viewExistingPatientRecord', ['patient_id' => $patient->id, 'vaccination_record_id' => $vaccinerecord->id]) }}" method="GET">
                                    <button type="submit" class="btn btn-info btn-sm"><strong>VIEW</strong></button>
                                </form>                            
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="button-container">
            <form action="{{ route('hcprovider.dashboard') }}" method="GET">
                <button type="submit" class="btn btn-primary" style="margin-left:350px;">Back to Dashboard</button> <!-- Back to Dashboard as a button -->
            </form>
        </div>
    </div>

    <div class="row mb-3" style="margin-left:200px;">
        <div class="col-md-6 custom-margin-right"> <!-- Applied custom margin class -->
            <p><strong class="label-color">Age:</strong> {{ $patient->age }}</p>
        </div>
        <div class="col-md-6 custom-margin-right"> <!-- Applied custom margin class -->
            <p><strong class="label-color">Email:</strong> {{ $patient->email }}</p>
        </div>

    </div>
</div>

@endsection 