@extends('layouts.hcprovider-sidebar')
@section('title', 'Admit Patient Record')

<style>
    .admit-patient-field{
        margin-left:50px;
        margin-top:20px;
    }
</style>
@section('content')
    <h2 style="margin-left: 50px; margin-bottom:20px;">Admit Patient</h2>
    <form action="{{ route('admit.patient.store') }}" method="POST" class="admit-patient-container" enctype="multipart/form-data">
        @csrf

        <div class="admit-patient-field">
            <span class="required-asterisk">*</span> <label for="full_name" class="admit-patient-label">Full Name:</label>
            @php
                $patient = session('patient_data');
            @endphp
            <p id="full_name" class="admit-patient-value">
                {{ $patient['first_name'] }} {{ $patient['middle_name'] ?? '' }} {{ $patient['last_name'] }}
            </p>
        </div>

        <div class="admit-patient-field">
            <span class="required-asterisk">*</span>  <label for="sex" class="admit-patient-label">Sex:</label>
            <p id="sex" class="admit-patient-value">{{ $patient['sex'] }}</p>
        </div>

        <div class="admit-patient-field">
            <span class="required-asterisk">*</span>  <label for="birthday" class="admit-patient-label">Birthday:</label>
            <p id="birthday" class="admit-patient-value">{{ $patient['birthday'] }}</p>
        </div>

        <!-- Vaccine Selection Dropdown -->
        <div class="admit-patient-field">
            <span class="required-asterisk">*</span>  <label for="vaccine_name" class="admit-patient-label">Select Vaccine:</label>
            <select id="vaccine_name" name="vaccine_name" class="form-control"  style="height:25px; width:400px;" required>
                <option value="" disabled selected>Select a vaccine</option>
                @forelse($vaccineStocks as $vaccine)
                    <option value="{{ $vaccine->stock_id }}">{{ $vaccine->vaccine_name }}</option>
                @empty
                    <option value="" disabled>No vaccines available</option>
                @endforelse
            </select>
        </div>
        
        
        <!-- Dose Number Dropdown -->
<div class="admit-patient-field">
    <span class="required-asterisk">*</span>  <label for="dose_number" class="admit-patient-label">Dose Number:</label>
    <select id="dose_number" name="dose_number" class="form-control" required style="height:25px; width:200px;">
        <option value="" disabled selected>Select Dose</option>
        <option value="First">First</option>
        <option value="Second">Second</option>
        <option value="Third">Third</option>
        <option value="Booster">Booster</option>
    </select>
</div>


        <!-- Next Dose Schedule Input (defaults to tomorrow) -->
        <div class="admit-patient-field">
            <label for="next_dose_schedule" class="admit-patient-label" style="margin-right: 12px;">Next Dose Schedule:</label>
            <input type="date" id="next_dose_schedule" name="next_dose_schedule" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"  style="height:25px;" class="form-control">
        </div>

        <div class="admit-patient-actions" style="margin-top: 150px; margin-left:200px;">
            <button type="submit" class="admit-patient-submit" 
                    style="margin: 0 10px; background-color: #C6E0FF; border: #C6E0FF; 
                           font-weight: bold; color: #000; width: 90px; border-radius: 0.3rem; margin-left:150px; margin-right:100px;">
                ADMIT
            </button>

            <button type="button" id="cancel-btn" class="btn btn-secondary" 
                    style="font-weight: bold; color: #000; width: 90px; border-radius: 0.3rem;">
                CANCEL
            </button>
        </div>
    </form>


@endsection