<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HCAdminController;
use App\Http\Controllers\HCProviderController;




Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);


// Admin Routes
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('admin/dashboard', function () {
        return view('admin.dashboard');})->name('admin.dashboard');
    Route::get('/admin/create-account', [AdminController::class, 'createAccountForm'])->name('admin.createAccount');
    Route::post('/admin/create-account', [AdminController::class, 'storeAccount'])->name('admin.storeAccount');

});

// Patient Routes
Route::middleware(['auth', 'role:Patient'])->group(function () {
    Route::get('patient/dashboard', function () {
        return view('patient.dashboard');
    })->name('patient.dashboard');


});

// HCAdmin Routes
Route::middleware(['auth', 'role:HCAdmin'])->group(function () {
    Route::get('hcadmin/dashboard', [HCAdminController::class, 'dashboard'])->name('hcadmin.dashboard');
    Route::post('hcadmin/add-stock', [HCAdminController::class, 'addStock'])->name('hcadmin.addStock');
    Route::post('/add-new-vaccine', [HCAdminController::class, 'addNewVaccine'])->name('hcadmin.addNewVaccine');

});

// HCProvider Routes
Route::middleware(['auth', 'role:HCProvider'])->group(function () {
    Route::get('hcprovider/dashboard', [HCProviderController::class, 'index'])->name('hcprovider.dashboard');
    Route::get('/hcprovider/find-patient', [HCProviderController::class, 'findPatient'])->name('hcprovider.findPatient');
    Route::get('/hcprovider/create-patient', [HCProviderController::class, 'showCreatePatientForm'])->name('hcprovider.createPatient');
   Route::post('/hcprovider/save-patient', [HCProviderController::class, 'savePatient'])->name('hcprovider.savePatient');
   Route::get('/hcprovider/admit-patient', [HCProviderController::class, 'admitPatient'])->name('hcprovider.admitPatient'); // No parameters
   Route::post('/hcprovider/admit-patient', [HCProviderController::class, 'storeAdmitPatient'])->name('admit.patient.store');
   Route::get('/hcprovider/patient/{id}/records', [HCProviderController::class, 'viewPatientRecord'])->name('hcprovider.viewPatientRecord');
   Route::get('/hcprovider/patient/{patient_id}/record/{vaccination_record_id}', [HCProviderController::class, 'viewExistingPatientRecord'])->name('hcprovider.viewExistingPatientRecord');
   Route::get('/hcprovider/selectPatient', [HCProviderController::class, 'selectPatient'])->name('hcprovider.selectPatient');
   Route::post('/hcprovider/existing-patient', [HCProviderController::class, 'storeExistingPatient'])->name('hcprovider.admitexisting');
   Route::get('/hcprovider/scheduled-patients', [HcProviderController::class, 'ScheduledPatient'])->name('hcprovider.scheduledpatient');
   Route::get('/hcprovider/find_refillpatient', [HCProviderController::class, 'findScheduledPatient'])->name('hcprovider.find_scheduledpatient');

});
