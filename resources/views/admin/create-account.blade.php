@extends('layouts.admin-sidebar')

@section('title', 'Create Account')

@section('content')
    <div class="container mt-4">
        <form id="create-account-form" method="POST" action="{{ route('admin.storeAccount') }}" class="custom-form-margin">
            @csrf
            <div class="mb-4">
                <label for="first_name" class="form-label custom-label">
                    <span class="required-asterisk">*</span> First Name:
                </label>
                <input type="text" class="form-control custom-input" id="first_name" name="first_name" placeholder="First Name" required>
            </div>
            <div class="mb-4">
                <label for="middle_name" class="form-label custom-label">
                    Middle Name:
                </label>
                <input type="text" class="form-control custom-input" id="middle_name" name="middle_name" placeholder="Middle Name (Optional)">
            </div>
            <div class="mb-4">
                <label for="last_name" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Last Name:
                </label>
                <input type="text" class="form-control custom-input" id="last_name" name="last_name" placeholder="Last Name" required>
            </div>
            <div class="mb-4">
                <label for="role" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Role:
                </label>
                <select class="form-control custom-input" id="role" name="role" required>
                    <option value="HCProvider">HCProvider</option>
                    <option value="HCAdmin">HCAdmin</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="email" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Email:
                </label>
                <input type="email" class="form-control custom-input" id="email" name="email" placeholder="Email Address" required>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-left:400px; background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold">SUBMIT</button>
        </form>
    </div>
@endsection
