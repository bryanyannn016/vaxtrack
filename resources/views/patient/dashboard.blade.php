@extends('layouts.patient-sidebar')

@section('title', 'Patient Dashboard')

@section('content')

<style>
    .custom-table {
        margin-top: 20px;
        width: 150%;
        height: 25%;
        border-collapse: collapse;
        
    }

    .custom-table th, .custom-table td {
        border: 1px solid #dee2e6;
        padding: 2px;
        text-align: center;
        font-size: 16px;
    }

    .custom-table th {
        background-color: #C6E0FF;
        font-weight: bold;
    }

    .custom-table tr:nth-child(even) {
        background-color: #ffffff;
    }

    .label-color {
        color: #286187;
    }
</style>

<div class="container mt-4">
    <div class="row mb-3">

        <div class="col-md-6 custom-margin-right"> <!-- Applied custom margin class -->
            <p><strong class="label-color">Name:</strong> {{ $patientDetails->first_name }} {{ $patientDetails->middle_name }} {{ $patientDetails->last_name }}</p>
        </div>
        <div class="col-md-6 custom-margin-right"> <!-- Applied custom margin class -->
            <p><strong class="label-color">Sex:</strong> {{ $patientDetails->sex }}</p>
        </div>

        <h3 style="margin-top:50px; color: #286187;"> Vaccination Records </h3>

        @if($patientNotFound)
            <p style="text-align:center; color:black; font-size: 16px; font-weight: bold; margin-top:50px;">Patient record not found.</p>
        @elseif($records->isEmpty())
            <p style="text-align:center; color: #286187;">No records found for this patient.</p>
        @else
            <table class="table table-bordered custom-table">
                <thead>
                    <tr>
                        <th>Vaccine</th>
                        <th>Dose Number</th>
                        <th>Administered Date</th>
                        <th>Administered By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $vaccinerecord)
                        <tr>
                            <td >{{ $vaccinerecord->vaccine_name }}</td>
                            <td >{{ $vaccinerecord->dose_number }}</td>
                            <td>{{ $vaccinerecord->administration_date }}</td>
                            <td>
                                @if($vaccinerecord->first_name || $vaccinerecord->last_name)
                                    {{ $vaccinerecord->first_name }} {{ $vaccinerecord->middle_name ? $vaccinerecord->middle_name : '' }} {{ $vaccinerecord->last_name }}
                                @else
                                    Not Available
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        
    </div>

    <div class="row mb-3" style="margin-left:50px;">
        <div class="col-md-6 custom-margin-right"> <!-- Applied custom margin class -->
            <p><strong class="label-color">Age:</strong> {{ $patientDetails->age }}</p>
        </div>
        <div class="col-md-6 custom-margin-right"> <!-- Applied custom margin class -->
            <p><strong class="label-color">Email:</strong> {{ $patientDetails->email }}</p>
        </div>

    </div>
</div>

@endsection
