<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient.user', 'doctor.user', 'department']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->orderBy('appointment_date', 'DESC')
                             ->orderBy('appointment_time', 'DESC')
                             ->paginate(15);

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'department_id' => $request->department_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'type' => $request->type ?? 'consultation',
            'notes' => $request->notes,
            'status' => 'scheduled',
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'appointment' => $appointment->load('patient.user', 'doctor.user'),
            'message' => 'تم حجز الموعد بنجاح',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment->update([
            'appointment_date' => $request->appointment_date ?? $appointment->appointment_date,
            'appointment_time' => $request->appointment_time ?? $appointment->appointment_time,
            'status' => $request->status ?? $appointment->status,
            'notes' => $request->notes ?? $appointment->notes,
        ]);

        return response()->json([
            'appointment' => $appointment->load('patient.user', 'doctor.user'),
            'message' => 'تم تحديث الموعد بنجاح',
        ]);
    }
}
