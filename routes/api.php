<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public authentication: self-registration is intentionally disabled for the demo.
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login'])
    ->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json([
            'id' => $request->user()->id,
            'name' => $request->user()->name,
        ]);
    });

    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);

    // Fail closed: sensitive endpoints are admin-only until finer-grained
    // policies and per-record ownership checks are implemented and tested.
    Route::middleware(function (Request $request, \Closure $next) {
        abort_unless($request->user()?->hasRole('admin'), 403);
        return $next($request);
    })->group(function () {
        Route::get('/patients', [App\Http\Controllers\Api\PatientController::class, 'index']);
        Route::get('/patients/{id}', [App\Http\Controllers\Api\PatientController::class, 'show']);
        Route::post('/patients', [App\Http\Controllers\Api\PatientController::class, 'store']);
        Route::put('/patients/{id}', [App\Http\Controllers\Api\PatientController::class, 'update']);
        Route::delete('/patients/{id}', [App\Http\Controllers\Api\PatientController::class, 'destroy']);

        Route::get('/doctors', [App\Http\Controllers\Api\DoctorController::class, 'index']);
        Route::get('/doctors/{id}', [App\Http\Controllers\Api\DoctorController::class, 'show']);

        Route::get('/appointments', [App\Http\Controllers\Api\AppointmentController::class, 'index']);
        Route::post('/appointments', [App\Http\Controllers\Api\AppointmentController::class, 'store']);
        Route::put('/appointments/{id}', [App\Http\Controllers\Api\AppointmentController::class, 'update']);

        Route::get('/invoices', [App\Http\Controllers\Api\InvoiceController::class, 'index']);
        Route::post('/invoices', [App\Http\Controllers\Api\InvoiceController::class, 'store']);

        Route::get('/dashboard/stats', [App\Http\Controllers\Api\DashboardController::class, 'stats']);
    });
});
