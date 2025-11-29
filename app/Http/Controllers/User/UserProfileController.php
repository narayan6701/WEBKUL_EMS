<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class UserProfileController extends Controller
{
    /**
     * All methods here require the user to be logged in.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for editing the user's profile (the dashboard).
     * Renamed from 'index' to 'edit' for clarity.
     */
    public function edit()
    {
        $user = Auth::user()->load(['addresses', 'qualifications', 'experiences']);
        return view('user.user_profile', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'dob' => 'required|date',
            'profile_picture' => 'nullable|image|max:2048',
            'permanent.line1' => 'required|string|max:255',
            'permanent.line2' => 'required|string|max:255',
            'permanent.city' => 'required|string|max:100',
            'permanent.state' => 'required|string|max:100',
            'current.line1' => 'required|string|max:255',
            'current.line2' => 'required|string|max:255',
            'current.city' => 'required|string|max:100',
            'current.state' => 'required|string|max:100',
            'qualifications' => 'nullable|array',
            'qualifications.*' => 'string|max:1000',
            'experiences' => 'nullable|array',
            'experiences.*' => 'string|max:1000',
        ]);

        // --- CHANGE DETECTION ---
        $changed = false;

        // 1. Name
        if ($data['name'] !== $user->name) {
            $changed = true;
        }

        // 2. DOB (Fix: Ensure both are strings in Y-m-d format)
        $userDob = $user->dob instanceof \DateTimeInterface ? $user->dob->format('Y-m-d') : $user->dob;
        if ($data['dob'] !== $userDob) {
            $changed = true;
        }

        // 3. Profile Picture
        if ($request->hasFile('profile_picture')) {
            $changed = true;
        }

        // 4. Addresses (Fix: Cast DB nulls to empty strings for comparison)
        $permanent = $user->addresses->where('type', 'permanent')->first();
        $current = $user->addresses->where('type', 'current')->first();

        $checkAddr = function($addr, $input) {
            if (!$addr) return true; // Address didn't exist, so it's a change
            if ((string)$addr->line1 !== (string)($input['line1'] ?? '')) return true;
            if ((string)$addr->line2 !== (string)($input['line2'] ?? '')) return true;
            if ((string)$addr->city !== (string)($input['city'] ?? '')) return true;
            if ((string)$addr->state !== (string)($input['state'] ?? '')) return true;
            return false;
        };

        if ($checkAddr($permanent, $data['permanent'])) $changed = true;
        if ($checkAddr($current, $data['current'])) $changed = true;

        // 5. Arrays (Qualifications / Experiences)
        $inQuals = array_values(array_filter(array_map('trim', $data['qualifications'] ?? []), fn($v) => $v !== ''));
        $exQuals = $user->qualifications->pluck('qualification')->map(fn($v) => trim($v))->filter()->values()->all();
        if ($inQuals !== $exQuals) $changed = true;

        $inExps = array_values(array_filter(array_map('trim', $data['experiences'] ?? []), fn($v) => $v !== ''));
        $exExps = $user->experiences->pluck('experience')->map(fn($v) => trim($v))->filter()->values()->all();
        if ($inExps !== $exExps) $changed = true;

        // --- RETURN IF NO CHANGES ---
        if (! $changed) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No changes to save'], 200);
            }
            return redirect()->route('dashboard')->with('status', 'No changes to save');
        }

        // --- SAVE CHANGES ---
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = $request->file('profile_picture')->store('images', 'public');
        }

        $user->fill([
            'name' => $data['name'],
            'dob' => $data['dob'],
        ])->save();

        $user->addresses()->updateOrCreate(['type' => 'permanent'], $data['permanent']);
        $user->addresses()->updateOrCreate(['type' => 'current'], $data['current']);

        $user->qualifications()->delete();
        if (!empty($data['qualifications'])) {
            $user->qualifications()->createMany(
                collect($data['qualifications'])->map(fn($q) => ['qualification' => $q])->all()
            );
        }

        $user->experiences()->delete();
        if (!empty($data['experiences'])) {
            $user->experiences()->createMany(
                collect($data['experiences'])->map(fn($e) => ['experience' => $e])->all()
            );
        }

        if ($request->expectsJson()) {
            $payload = ['message' => 'Profile updated successfully!'];
            if ($user->profile_picture) {
                $payload['profile_picture_url'] = asset('storage/' . $user->profile_picture);
            }
            return response()->json($payload, 200);
        }

        return redirect()->route('user.user_profile')->with('status', 'Profile updated successfully!');
    }
}
