@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <a href="{{ route('appointments.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Appointments
    </a>

    <!-- Appointment Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-white text-center sm:text-left">
                    <h1 class="text-2xl font-bold">
                        {{ $appointment->appointment_date->format('F d, Y') }}
                    </h1>
                    <p class="opacity-90 text-lg">{{ $appointment->appointment_time }}</p>
                </div>
                <span class="px-4 py-2 bg-white rounded-lg text-blue-600 font-medium
                    {{ $appointment->status == 'completed' ? 'bg-green-100 text-green-700' : 
                      ($appointment->status == 'cancelled' ? 'bg-red-100 text-red-700' : 
                      ($appointment->status == 'confirmed' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700')) }}">
                    {{ ucfirst($appointment->status) }}
                </span>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Patient Info -->
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Patient
                </h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                        <span class="text-indigo-600 font-medium">{{ substr($appointment->patient->user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $appointment->patient->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $appointment->patient->patient_code }}</p>
                    </div>
                </div>
            </div>

            <!-- Doctor Info -->
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Doctor
                </h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                        <span class="text-emerald-600 font-medium">{{ substr($appointment->doctor->user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $appointment->doctor->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $appointment->doctor->specialization }}</p>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Details
                </h3>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium">Type:</span> {{ ucfirst($appointment->type ?? 'consultation') }}</p>
                    @if($appointment->department)
                    <p><span class="font-medium">Department:</span> {{ $appointment->department->name }}</p>
                    @endif
                    <p><span class="font-medium">Created:</span> {{ $appointment->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            <!-- Notes -->
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Notes
                </h3>
                <p class="text-sm text-gray-600">{{ $appointment->notes ?? 'No notes' }}</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-6 py-4 bg-gray-50 flex flex-wrap gap-2">
            @if($appointment->status == 'scheduled')
            <form action="{{ route('appointments.confirm', $appointment) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Confirm
                </button>
            </form>
            @endif
            
            @if(in_array($appointment->status, ['scheduled', 'confirmed']))
            <form action="{{ route('appointments.complete', $appointment) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Mark Completed
                </button>
            </form>
            @endif
            
            <a href="{{ route('appointments.edit', $appointment) }}" 
               class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition">
                Edit
            </a>
            
            <a href="{{ route('appointments.index') }}" 
               class="px-4 py-2 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                Back
            </a>
        </div>
    </div>
</div>
@endsection
