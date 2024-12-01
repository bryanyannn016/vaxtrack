@extends('layouts.hcprovider-sidebar')

@section('title', 'Patient Record Details')

@section('content')

<style>
    .label-color {
        color: #286187;
    }
    .table thead th {
        background-color: #C6E0FF;
        border: 1px solid #dee2e6;
        text-align: center;
    }
    .table tbody td {
        border: 1px solid #dee2e6;
        text-align: center;
    }
</style>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6 custom-margin-right" style="margin-right:200px;">
            <p><strong class="label-color">Name:</strong> 
               {{ $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}</p>
        </div>
        <div class="col-md-6 custom-margin-right">
            <p><strong class="label-color">Age:</strong> {{ $patient->age }}</p>
        </div>

        <div class="mt-4">
            <h3 style="margin-top:90px; color:#286187">Vaccine Record</h3>
            <table class="table table-bordered" style="border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="width:300px;">Vaccine Name</th>
                        <th style="width:150px;">Dose Number</th>
                        <th style="width:150px;">Administered Date</th>
                        <th style="width:250px;">Administered By</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $vaccinationrecord->vaccine_name }}</td>
                        <td>{{ $vaccinationrecord->dose_number }}</td>
                        <td>{{ $vaccinationrecord->administration_date }}</td>
                        <td>
                            @if($vaccinationrecord->administeredBy)
                                {{ $vaccinationrecord->administeredBy->first_name }} 
                                {{ $vaccinationrecord->administeredBy->middle_name }} 
                                {{ $vaccinationrecord->administeredBy->last_name }}
                            @else
                                Not Available
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>            

            @if(!$vaccinationrecord)  <!-- Check if there's no record -->
                <p>No vaccine found for this record.</p>
            @endif
        </div>

    </div>

    <div class="row mb-3">
        <div class="col-md-6 text-end custom-margin-right">
            <p><strong class="label-color">Address:</strong> {{ $patient->address }}</p>
        </div>
        <div class="col-md-6 text-end">
            <p><strong class="label-color">Sex:</strong> {{ $patient->sex }}</p>
        </div>
    </div>
</div>

@endsection
