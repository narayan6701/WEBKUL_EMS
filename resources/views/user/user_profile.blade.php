@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

@php
    $permanentAddress = $user->addresses->where('type', 'permanent')->first();
    $currentAddress = $user->addresses->where('type', 'current')->first();

    $states = [
    'Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana',
    'Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur',
    'Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana',
    'Tripura','Uttarakhand','Uttar Pradesh','West Bengal','Andaman and Nicobar Islands','Chandigarh',
    'Dadra and Nagar Haveli and Daman and Diu','New Delhi','Jammu and Kashmir','Ladakh','Lakshadweep','Puducherry'
    ];
@endphp

<div class="dashboard-container">
    <header class="dashboard-header">
        <h1>Your Profile</h1>
        <form method="POST" action="{{ route('user_logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </header>

    <form id="profileForm" action="{{ route('user_profile') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        @if (session('status'))
            <div style="margin-bottom: 1.5rem;">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div style="margin-bottom: 1.5rem;">
                @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
            </div>
        @endif

        {{-- This is the new three-column responsive grid --}}
        <div class="dashboard-grid">

            {{-- Column 1: Profile Summary --}}
            <div class="detail-card">
                <div class="profile-picture-upload">
                    <img id="avatarPreview" src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture">
                    <label for="profile_picture" class="upload-btn">Upload Picture</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                </div>
                <div class="form-group-user">
                    <label for="name">Full Name</label>
                    <div class="editable-field">
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        <span class="edit-icon">✏️</span>
                    </div>
                </div>
                <div class="form-group-user">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" value="{{ $user->email }}" disabled>
                </div>
                <div class="form-group-user">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob" value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}" required>
                </div>
            </div>

            {{-- Column 2: Addresses --}}
            <div class="detail-card">
                <h3>Address Information</h3>
                <h4>Permanent Address</h4>
                <div class="form-group-user editable-field"><input type="text" name="permanent[line1]" placeholder="Line 1" value="{{ old('permanent.line1', $permanentAddress->line1 ?? '') }}" required><span class="edit-icon">✏️</span></div>
                <div class="form-group-user editable-field"><input type="text" name="permanent[line2]" placeholder="Line 2" value="{{ old('permanent.line2', $permanentAddress->line2 ?? '') }}" ><span class="edit-icon">✏️</span></div>
                <div class="form-group-user editable-field"><input type="text" name="permanent[city]" placeholder="City" value="{{ old('permanent.city', $permanentAddress->city ?? '') }}" required><span class="edit-icon">✏️</span></div>
                
                {{-- REPLACED INPUT WITH SELECT DROPDOWN --}}
                <div class="form-group-user editable-field">
                    <select name="permanent[state]" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="" disabled selected>Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state }}" {{ old('permanent.state', $permanentAddress->state ?? '') == $state ? 'selected' : '' }}>
                                {{ $state }}
                            </option>
                        @endforeach
                    </select>
                    <span class="edit-icon">✏️</span>
                </div>
                
                <h4 style="margin-top: 1.5rem;">Current Address</h4>
                <div class="form-group-user editable-field"><input type="text" name="current[line1]" placeholder="Line 1" value="{{ old('current.line1', $currentAddress->line1 ?? '') }}" required><span class="edit-icon">✏️</span></div>
                <div class="form-group-user editable-field"><input type="text" name="current[line2]" placeholder="Line 2" value="{{ old('current.line2', $currentAddress->line2 ?? '') }}" ><span class="edit-icon">✏️</span></div>
                <div class="form-group-user editable-field"><input type="text" name="current[city]" placeholder="City" value="{{ old('current.city', $currentAddress->city ?? '') }}" required><span class="edit-icon">✏️</span></div>
                
                {{-- REPLACED INPUT WITH SELECT DROPDOWN --}}
                <div class="form-group-user editable-field">
                    <select name="current[state]" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="" disabled selected>Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state }}" {{ old('current.state', $currentAddress->state ?? '') == $state ? 'selected' : '' }}>
                                {{ $state }}
                            </option>
                        @endforeach
                    </select>
                    <span class="edit-icon">✏️</span>
                </div>
            </div>

            {{-- Column 3: Qualifications & Experience --}}
            <div class="details-column">
                <div class="detail-card">
                    <h3>Qualifications</h3>
                    <div id="qualificationsList" class="dynamic-list">
                        @forelse($user->qualifications as $qual)
                            <div class="list-item"><input type="text" name="qualifications[]" value="{{ $qual->qualification }}" required><button type="button" class="btn-remove">−</button></div>
                        @empty
                            <div class="list-item"><input type="text" name="qualifications[]" placeholder="e.g. B.Sc in CompSci" required><button type="button" class="btn-remove">−</button></div>
                        @endforelse
                    </div>
                    <button type="button" class="btn-add" id="addQualificationBtn">+</button>
                </div>
                <div class="detail-card">
                    <h3>Work Experience</h3>
                    <div id="experiencesList" class="dynamic-list">
                        @forelse($user->experiences as $exp)
                            <div class="list-item"><input type="text" name="experiences[]" value="{{ $exp->experience }}" required><button type="button" class="btn-remove">−</button></div>
                        @empty
                            <div class="list-item"><input type="text" name="experiences[]" placeholder="e.g. 3 years at Acme Inc" required><button type="button" class="btn-remove">−</button></div>
                        @endforelse
                    </div>
                    <button type="button" class="btn-add" id="addExperienceBtn">+</button>
                </div>
            </div>

            {{-- Form actions are now part of the grid, spanning all columns --}}
            <div class="form-actions">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
</div>
<script src="{{ asset('js/user_profile.js') }}" defer></script>
@endsection
