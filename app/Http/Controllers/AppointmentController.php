<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Department;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['patient.user', 'doctor.user', 'department']);
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('appointment_date', $request->date);
        }
        
        // Filter by doctor
        if ($request->has('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        // Filter by patient
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }
        
        $appointments = $query->orderBy('appointment_date', 'DESC')->orderBy('appointment_time', 'DESC')->paginate(15);
        
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        $departments = Department::all();
        
        return view('appointments.create', compact('patients', 'doctors', 'departments'));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required'],
            'type' => ['nullable', 'in:consultation,follow_up,emergency,checkup'],
            'notes' => ['nullable', 'string'],
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

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'doctor.user', 'department', 'medicalRecord', 'invoice']);
        
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        $departments = Department::all();
        
        return view('appointments.edit', compact('appointment', 'patients', 'doctors', 'departments'));
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required'],
            'type' => ['nullable', 'in:consultation,follow_up,emergency,checkup'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:scheduled,confirmed,completed,cancelled,no_show'],
        ]);

        $appointment->update([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'department_id' => $request->department_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'type' => $request->type,
            'notes' => $request->notes,
            'status' => $request->status ?? $appointment->status,
        ]);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified appointment from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Update appointment status.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => ['required', 'in:scheduled,confirmed,completed,cancelled,no_show'],
        ]);

        $appointment->update(['status' => $request->status]);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment status updated.');
    }

    /**
     * Confirm appointment.
     */
    public function confirm(Appointment $appointment)
    {
        $appointment->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment confirmed.');
    }

    /**
     * Complete appointment.
     */
    public function complete(Appointment $appointment)
    {
        $appointment->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment completed.');
    }
}
