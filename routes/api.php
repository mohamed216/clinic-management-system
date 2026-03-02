<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    
    // Patients
    Route::get('/patients', [App\Http\Controllers\Api\PatientController::class, 'index']);
    Route::get('/patients/{id}', [App\Http\Controllers\Api\PatientController::class, 'show']);
    Route::post('/patients', [App\Http\Controllers\Api\PatientController::class, 'store']);
    Route::put('/patients/{id}', [App\Http\Controllers\Api\PatientController::class, 'update']);
    Route::delete('/patients/{id}', [App\Http\Controllers\Api\PatientController::class, 'destroy']);
    
    // Doctors
    Route::get('/doctors', [App\Http\Controllers\Api\DoctorController::class, 'index']);
    Route::get('/doctors/{id}', [App\Http\Controllers\Api\DoctorController::class, 'show']);
    
    // Appointments
    Route::get('/appointments', [App\Http\Controllers\Api\AppointmentController::class, 'index']);
    Route::post('/appointments', [App\Http\Controllers\Api\AppointmentController::class, 'store']);
    Route::put('/appointments/{id}', [App\Http\Controllers\Api\AppointmentController::class, 'update']);
    
    // Invoices
    Route::get('/invoices', [App\Http\Controllers\Api\InvoiceController::class, 'index']);
    Route::post('/invoices', [App\Http\Controllers\Api\InvoiceController::class, 'store']);
    
    // Dashboard
    Route::get('/dashboard/stats', [App\Http\Controllers\Api\DashboardController::class, 'stats']);
});
