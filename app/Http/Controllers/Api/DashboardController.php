<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        $patientsCount = Patient::count();
        $doctorsCount = Doctor::count();
        
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $todayCompleted = Appointment::whereDate('appointment_date', today())
                                   ->where('status', 'completed')->count();
        
        $monthlyRevenue = Invoice::where('invoice_status', 'paid')
                                ->whereMonth('created_at', now()->month)
                                ->sum('total_with_tax');
        
        $pendingInvoices = Invoice::where('invoice_status', 'pending')
                                 ->sum('total_with_tax');

        return response()->json([
            'patients' => $patientsCount,
            'doctors' => $doctorsCount,
            'today_appointments' => $todayAppointments,
            'completed_appointments' => $todayCompleted,
            'monthly_revenue' => $monthlyRevenue,
            'pending_invoices' => $pendingInvoices,
        ]);
    }
}
