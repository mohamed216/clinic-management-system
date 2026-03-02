<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['patient.user', 'doctor.user']);
        
        if ($request->has('status')) {
            $query->where('invoice_status', $request->status);
        }

        $invoices = $query->orderBy('created_at', 'DESC')->paginate(15);

        $totalPaid = Invoice::where('invoice_status', 'paid')->sum('total_with_tax');
        $totalPending = Invoice::where('invoice_status', 'pending')->sum('total_with_tax');

        return response()->json([
            'invoices' => $invoices,
            'totals' => [
                'paid' => $totalPaid,
                'pending' => $totalPending,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'price' => 'required|numeric|min:0',
        ]);

        $price = $request->price;
        $discount = $request->discount_value ?? 0;
        $taxRate = $request->tax_rate ?? 15;
        
        $subtotal = $price - $discount;
        $tax = $subtotal * ($taxRate / 100);
        $total = $subtotal + $tax;

        $invoice = Invoice::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'invoice_status' => 'pending',
            'price' => $price,
            'discount_value' => $discount,
            'tax_rate' => $taxRate,
            'tax_value' => $tax,
            'total_with_tax' => $total,
            'amount_paid' => 0,
            'remaining_amount' => $total,
        ]);

        return response()->json([
            'invoice' => $invoice->load('patient.user'),
            'message' => 'تم إنشاءالفاتورة بنجاح',
        ], 201);
    }
}
