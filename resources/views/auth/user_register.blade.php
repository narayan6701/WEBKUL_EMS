@extends('layouts.app')

@section('title', 'Register')

@section('content')

  @php
    $states = [
      'Andhra Pradesh',
      'Arunachal Pradesh',
      'Assam',
      'Bihar',
      'Chhattisgarh',
      'Goa',
      'Gujarat',
      'Haryana',
      'Himachal Pradesh',
      'Jharkhand',
      'Karnataka',
      'Kerala',
      'Madhya Pradesh',
      'Maharashtra',
      'Manipur',
      'Meghalaya',
      'Mizoram',
      'Nagaland',
      'Odisha',
      'Punjab',
      'Rajasthan',
      'Sikkim',
      'Tamil Nadu',
      'Telangana',
      'Tripura',
      'Uttarakhand',
      'Uttar Pradesh',
      'West Bengal',
      'Andaman and Nicobar Islands',
      'Chandigarh',
      'Dadra and Nagar Haveli and Daman and Diu',
      'New Delhi',
      'Jammu and Kashmir',
      'Ladakh',
      'Lakshadweep',
      'Puducherry'
    ];
  @endphp

  @if ($errors->any())
    <p style="color: #dc3545; font-size: 0.9rem; text-align: center;">
      {{ $errors->first() }}
    </p>
  @endif

  <div class="mega">

    <form id="registerForm" action="{{ route('user_register') }}" method="POST" enctype="multipart/form-data"
      class="register-form">
      @csrf

      <div class="form-left">
        <!-- Personal -->
        <div class="block">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" placeholder="Full name" required>

          <label for="dob">Date of Birth</label>
          <input type="date" id="dob" name="dob" placeholder="YYYY-MM-DD" required>
        </div>

        <!-- Account -->
        <div class="block">

          <div class="pair">
            <div><label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="name@example.com" required>
          </div>

          <div>
            <label for="phone">Phone</label>
            <input type="phone" id="phone" name="phone" placeholder="1234567890" required>
          </div>
          </div>


          <div class="pair">
            <div>
              <label for="password">Password</label>
              <input type="password" id="password" name="password" placeholder="Create a password" required>
            </div>
            <div>
              <label for="password_confirmation">Re - Password</label>
              <input type="password" id="password_confirmation" name="password_confirmation"
                placeholder="Confirm your password" required>
            </div>
          </div>
        </div>

        <!-- Qualifications (allow multiple) -->
        <div class="block">
          <label>Qualifications</label>
          <div id="qualificationsList">
            <div class="qual-item">
              <div class="qual-row">
                <input type="text" name="qualifications[]" placeholder="e.g. B.Sc in Computer Science" required>
                <!-- Add button moved to right of input, shows plus icon -->
                <button type="button" id="addQualificationBtn" class="btn-add icon-btn"
                  aria-label="Add qualification">+</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Experiences -->
        <div class="block">
          <label>Experiences</label>
          <div id="experiencesList">
            <div class="exp-item">
              <div class="exp-row">
                <input type="text" name="experiences[]" placeholder="e.g. 3 years at Company XYZ" required>
                <!-- Add button moved to right of input, shows plus icon -->
                <button type="button" id="addExperienceBtn" class="btn-add icon-btn"
                  aria-label="Add experience">+</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Permanent Address -->
        <fieldset>
          <legend>Permanent Address</legend>
          <div class="pair">
            <label for="perm_line1">Line 1</label>
            <input type="text" id="perm_line1" name="permanent[line1]" placeholder="House / Street / Building" required>

            <label for="perm_line2">Line 2</label>
            <input type="text" id="perm_line2" name="permanent[line2]" placeholder="Apartment / Landmark (optional)"
              required>
          </div>

          <div class="pair">
            <div>
              <label for="perm_city">City</label>
              <input type="text" id="perm_city" name="permanent[city]" placeholder="City name" required>
            </div>
            <div>
              <label for="perm_state">State</label>
              <select id="perm_state" name="permanent[state]" required>
                <option value="">Select State</option>
                @foreach($states as $state)
                  <option value="{{ $state }}">{{ $state }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </fieldset>

        <!-- Current Address -->
        <fieldset>
          <legend>Current Address</legend>
          <div class="pair">
            <label for="curr_line1">Line 1</label>
            <input type="text" id="curr_line1" name="current[line1]" placeholder="House / Street / Building" required>

            <label for="curr_line2">Line 2</label>
            <input type="text" id="curr_line2" name="current[line2]" placeholder="Apartment / Landmark (optional)"
              required>
          </div>

          <div class="pair">
            <div>
              <label for="curr_city">City</label>
              <input type="text" id="curr_city" name="current[city]" placeholder="City name" required>
            </div>
            <div>
              <label for="curr_state">State</label>
              <select id="curr_state" name="current[state]" required>
                <option value="">Select State</option>
                @foreach($states as $state)
                  <option value="{{ $state }}">{{ $state }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </fieldset>
      </div>
      <aside class="form-right">
        <div class="profile-block">
          <div class="avatar-preview">
            <img id="avatarPreview" src="{{ asset('images/default_profile.jpg') }}" alt="Profile preview">
          </div>

          <!-- The actual file input, now visually hidden. It MUST come before the label. -->
          <input type="file" id="profile_picture" name="profile_picture" class="visually-hidden" accept="image/*"
            required>

          <!-- The visible, styled label that acts as our button -->
          <label for="profile_picture" class="upload-btn">Choose Image</label>

        </div>
        <div class="action-group">
          <button type="submit" class="btn btn-primary">Sign Up</button>
          <button type="reset" id="resetBtn" class="btn btn-reset">Reset</button>
        </div>
        <div class="links" style="margin-top:0.75rem;">
          <a href="/user_login">Login as Employee</a>
          <a href="/admin_login">Login as Admin</a>
        </div>
      </aside>
    </form>
  </div>

  <script src="{{ asset('js/user_register.js') }}"></script>

@endsection