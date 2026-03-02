@extends('layouts.app')

@section('title', 'Patient Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <a href="{{ route('patients.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Patients
    </a>

    <!-- Patient Info Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8">
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center">
                    <span class="text-3xl font-bold text-indigo-600">{{ substr($patient->user->name, 0, 1) }}</span>
                </div>
                <div class="text-center sm:text-left text-white">
                    <h1 class="text-2xl font-bold">{{ $patient->user->name }}</h1>
                    <p class="opacity-90">{{ $patient->patient_code }}</p>
                    <div class="flex items-center justify-center sm:justify-start gap-3 mt-2">
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
                            {{ $patient->gender ? ucfirst($patient->gender) : '-' }}
                        </span>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
                            {{ $patient->age ? $patient->age . ' years old' : '-' }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            {{ $patient->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($patient->status) }}
                        </span>
                    </div>
                </div>
                <div class="sm:ml-auto flex gap-2">
                    <a href="{{ route('patients.edit', $patient) }}" 
                       class="px-4 py-2 bg-white text-indigo-600 rounded-lg hover:bg-indigo-50 transition">
                        Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Contact Info -->
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Contact
                </h3>
                <div class="space-y-2 text-sm">
                    <p class="text-gray-600">{{ $patient->user->email }}</p>
                    <p class="text-gray-600">{{ $patient->user->phone ?? 'No phone' }}</p>
                </div>
            </div>

            <!-- Medical Info -->
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Medical
                </h3>
                <div class="space-y-2 text-sm">
                    <p class="text-gray-600"><span class="font-medium">Blood:</span> {{ $patient->blood_group ?? '-' }}</p>
                    <p class="text-gray-600"><span class="font-medium">DOB:</span> {{ $patient->date_of_birth ?? '-' }}</p>
                </div>
            </div>

            <!-- Address -->
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Address
                </h3>
                <p class="text-sm text-gray-600">
                    {{ $patient->address ?? '-' }}<br>
                    {{ $patient->city ?? '' }} {{ $patient->state ? ', ' . $patient->state : '' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="border-b border-gray-100">
            <nav class="flex gap-8 px-6">
                <button class="py-4 border-b-2 border-indigo-600 text-indigo-600 font-medium">
                    Appointments
                </button>
            </nav>
        </div>
        <div class="p-6">
            @if($patient->appointments->count() > 0)
                <div class="space-y-4">
                    @foreach($patient->appointments as $appointment)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ $appointment->doctor->user->name ?? 'Doctor' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $appointment->status == 'completed' ? 'bg-green-100 text-green-700' : 
                              ($appointment->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No appointments found</p>
            @endif
        </div>
    </div>
</div>
@endsection
