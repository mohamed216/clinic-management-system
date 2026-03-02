<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with(['user', 'department']);
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $doctors = $query->orderBy('created_at', 'DESC')->paginate(15);

        return response()->json($doctors);
    }

    public function show($id)
    {
        $doctor = Doctor::with(['user', 'department', 'appointments'])->findOrFail($id);
        return response()->json($doctor);
    }
}
