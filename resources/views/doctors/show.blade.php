@extends('layouts.app')

@section('title', 'Doctor Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <a href="{{ route('doctors.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Doctors
    </a>

    <!-- Doctor Info Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-8">
            <div class="flex flex-col sm:flex-row items-center gap-6">
                @if($doctor->avatar)
                <img src="{{ Storage::url($doctor->avatar) }}" alt="{{ $doctor->user->name }}" 
                     class="w-24 h-24 rounded-full object-cover border-4 border-white">
                @else
                <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center border-4 border-white">
                    <span class="text-3xl font-bold text-emerald-600">{{ substr($doctor->user->name, 0, 1) }}</span>
                </div>
                @endif
                <div class="text-center sm:text-left text-white">
                    <h1 class="text-2xl font-bold">{{ $doctor->user->name }}</h1>
                    <p class="opacity-90">{{ $doctor->specialization }}</p>
                    <div class="flex items-center justify-center sm:justify-start gap-3 mt-2">
                        @if($doctor->department)
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
                            {{ $doctor->department->name }}
                        </span>
                        @endif
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            {{ $doctor->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($doctor->status) }}
                        </span>
                    </div>
                </div>
                <div class="sm:ml-auto flex gap-2">
                    <a href="{{ route('doctors.edit', $doctor) }}" 
                       class="px-4 py-2 bg-white text-emerald-600 rounded-lg hover:bg-emerald-50 transition">
                        Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Contact -->
            <div class="space-y-2">
                <h3 class="font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Contact
                </h3>
                <p class="text-sm text-gray-600">{{ $doctor->user->email }}</p>
                <p class="text-sm text-gray-600">{{ $doctor->user->phone ?? 'No phone' }}</p>
            </div>

            <!-- Professional -->
            <div class="space-y-2">
                <h3 class="font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Professional
                </h3>
                <p class="text-sm text-gray-600"><span class="font-medium">Experience:</span> {{ $doctor->experience ?? '-' }}</p>
                <p class="text-sm text-gray-600"><span class="font-medium">Fee:</span> ${{ number_format($doctor->consultation_fee ?? 0) }}</p>
            </div>

            <!-- Qualification -->
            <div class="space-y-2">
                <h3 class="font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Qualification
                </h3>
                <p class="text-sm text-gray-600">{{ $doctor->qualification ?? '-' }}</p>
            </div>

            <!-- Bio -->
            <div class="space-y-2">
                <h3 class="font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Bio
                </h3>
                <p class="text-sm text-gray-600">{{ $doctor->bio ?? 'No bio available' }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Recent Appointments</h2>
        </div>
        <div class="p-6">
            @if($doctor->appointments->count() > 0)
                <div class="space-y-4">
                    @foreach($doctor->appointments as $appointment)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                <span class="text-emerald-600 font-medium">{{ substr($appointment->patient->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $appointment->patient->user->name }}</p>
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
