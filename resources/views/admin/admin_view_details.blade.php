
@extends('layouts.app')
@section('title', 'View Details')
@section('content')
<div class="dashboard-container">
    <header class="dashboard-header">
        <h1>Viewing Employee: {{ $employee->name }}</h1>
        <a href="{{ route('admin_dashboard') }}">Back to List</a>
    </header>

    <!-- Main Profile Card -->
<div class="user-profile-card">
    <div class="profile-picture">
        <img src="{{ asset('storage/' . $employee->profile_picture) }}" alt="Profile Picture">
    </div>
    <div class="profile-details">
        <h2>{{ $employee->name }}</h2>
        <p><strong>Email:</strong> {{ $employee->email }}</p>
        <p><strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse($employee->dob)->format('F j, Y') }}</p>
    </div>
</div>

<!-- Additional Details Section -->
<div class="details-grid">
    @php
        $permanentAddress = $employee->addresses->where('type', 'permanent')->first();
        $currentAddress = $employee->addresses->where('type', 'current')->first();
    @endphp

    <!-- Addresses Card -->
    <div class="detail-card">
        <h3>Address Information</h3>
        @if($permanentAddress)
            <h4>Permanent Address</h4>
            <p>{{ $permanentAddress->line1 }}{{ $permanentAddress->line2 ? ', ' . $permanentAddress->line2 : '' }}<br>
               {{ $permanentAddress->city }}, {{ $permanentAddress->state }}</p>
        @endif

        @if($currentAddress)
            <h4 style="margin-top: 1rem;">Current Address</h4>
            <p>{{ $currentAddress->line1 }}{{ $currentAddress->line2 ? ', ' . $currentAddress->line2 : '' }}<br>
               {{ $currentAddress->city }}, {{ $currentAddress->state }}</p>
        @endif
    </div>

    <!-- Qualifications Card -->
    <div class="detail-card">
        <h3>Qualifications</h3>
        @if($employee->qualifications->isNotEmpty())
            <ul>
                @foreach($employee->qualifications as $qualification)
                    <li>{{ $qualification->qualification }}</li>
                @endforeach
            </ul>
        @else
            <p>No qualifications listed.</p>
        @endif
    </div>

    <!-- Experience Card -->
    <div class="detail-card">
        <h3>Work Experience</h3>
        @if($employee->experiences->isNotEmpty())
            <ul>
                @foreach($employee->experiences as $experience)
                    <li>{{ $experience->experience }}</li>
                @endforeach
            </ul>
        @else
            <p>No work experience listed.</p>
        @endif
    </div>
</div>
</div>
@endsection