<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\MedicalRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $totalAppointments = Appointment::count();
        $totalRevenue = Invoice::where('status', 'paid')->sum('paid_amount');
        
        // Today's appointments
        $todayAppointments = Appointment::whereDate('appointment_date', Carbon::today())
            ->with(['patient.user', 'doctor.user'])
            ->orderBy('appointment_time')
            ->get();
        
        // Recent appointments
        $recentAppointments = Appointment::with(['patient.user', 'doctor.user'])
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();
        
        // Pending appointments
        $pendingAppointments = Appointment::where('status', 'pending')
            ->with(['patient.user', 'doctor.user'])
            ->orderBy('appointment_date')
            ->limit(10)
            ->get();
        
        // Low stock medicines
        $lowStockMedicines = Medicine::whereColumn('stock_quantity', '<=', 'reorder_level')
            ->where('status', 'active')
            ->get();
        
        // Monthly revenue (last 6 months)
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->selectRaw('DATE_FORMAT(invoice_date, "%Y-%m") as month, SUM(paid_amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'DESC')
            ->limit(6)
            ->get();
        
        // Appointments by status
        $appointmentsByStatus = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        // New patients this month
        $newPatientsThisMonth = Patient::whereMonth('created_at', Carbon::now()->month)
            ->count();
        
        return view('admin.dashboard', compact(
            'totalPatients',
            'totalDoctors',
            'totalAppointments',
            'totalRevenue',
            'todayAppointments',
            'recentAppointments',
            'pendingAppointments',
            'lowStockMedicines',
            'monthlyRevenue',
            'appointmentsByStatus',
            'newPatientsThisMonth'
        ));
    }
}
