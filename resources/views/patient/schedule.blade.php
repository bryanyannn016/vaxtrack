@extends('layouts.patient-sidebar')

@section('title', 'Upcoming Vaccination Schedule')

@section('content')

<style>
    .custom-table {
        margin-top: 20px;
        width: 80%;
        border-collapse: collapse;
        height:10%
    }

    .custom-table th, .custom-table td {
        border: 1px solid #dee2e6;
        padding: 10px;
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

    .no-schedule {
        text-align: center;
        color: #286187;
        font-size: 16px;
        font-weight: bold;
        margin-top: 50px;
    }
</style>

<h4 style=" color: #286187; font-size:20px;">Your Upcoming Vaccination Schedule</h4>
<div class="container mt-4">
    @if($patientNotFound)
        <p class="no-schedule">Patient record not found.</p>
    @elseif($schedules->isEmpty())
        <p class="no-schedule">No upcoming schedules found.</p>
    @else
        <table class="table table-bordered custom-table">
            <thead>
                <tr>    
                    <th>Vaccine</th>
                    <th>Scheduled Date</th>
                    <th>Next Dose</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->vaccine_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('F j, Y') }}</td>
                        <td>{{ $schedule->next_dose }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
