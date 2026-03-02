<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('user');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('patient_code', 'like', "%{$search}%");
            });
        }

        $patients = $query->orderBy('created_at', 'DESC')->paginate(15);

        return response()->json($patients);
    }

    public function show($id)
    {
        $patient = Patient::with(['user', 'appointments', 'invoices'])->findOrFail($id);
        return response()->json($patient);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        $patient = Patient::create([
            'user_id' => $user->id,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'address' => $request->address,
            'status' => 'active',
        ]);

        return response()->json([
            'patient' => $patient->load('user'),
            'message' => 'تم إضافة المريض بنجاح',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $patient->user_id,
        ]);

        $patient->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $patient->update([
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'address' => $request->address,
            'status' => $request->status ?? 'active',
        ]);

        return response()->json([
            'patient' => $patient->load('user'),
            'message' => 'تم تحديث البيانات بنجاح',
        ]);
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->user()->delete();
        
        return response()->json([
            'message' => 'تم حذف المريض بنجاح',
        ]);
    }
}
