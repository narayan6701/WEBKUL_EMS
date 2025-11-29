<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Address;
use App\Models\Qualification;
use App\Models\Experience;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserRegisterController extends Controller
{
    /**
     * Store registration form data.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'dob' => 'required|date',
            'email' => 'required|email|max:150|unique:users,email',
            
            // This 'confirmed' rule handles the password mismatch validation
            'password' => 'required|confirmed', 
            
            // This 'max:2048' rule ensures the file is under 2MB (2048 kilobytes)
            'profile_picture' => 'required|image|max:2048',

            'permanent.line1' => 'required|string|max:255',
            'permanent.line2' => 'required|string|max:255',
            'permanent.city' => 'required|string|max:100',
            'permanent.state' => 'required|string|max:100',

            'current.line1' => 'required|string|max:255',
            'current.line2' => 'required|string|max:255',
            'current.city' => 'required|string|max:100',
            'current.state' => 'required|string|max:100',

            'qualifications' => 'required|array|min:1',
            'qualifications.*' => 'required|string|max:1000',
            'experiences' => 'required|array|min:1',
            'experiences.*' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // handle profile upload (store on 'public' disk under images/)
            $profilePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePath = $request->file('profile_picture')->store('images', 'public');

                // debug / verify the file was actually written
                if (! Storage::disk('public')->exists($profilePath)) {
                    // report so you get a log entry
                    report(new \RuntimeException("Profile upload failed: file not found at storage/app/public/{$profilePath}"));
                }
            }

            // create user (User model handles password hashing if cast/mutator present)
            $user = User::create([
                'name' => $data['name'],
                'dob' => $data['dob'],
                'email' => $data['email'],
                'password' => $data['password'],
                'profile_picture' => $profilePath, // null -> DB default will apply if set
            ]);

            // addresses
            $user->addresses()->create([
                'type' => 'permanent',
                'line1' => $data['permanent']['line1'],
                'line2' => $data['permanent']['line2'] ?? null,
                'city' => $data['permanent']['city'],
                'state' => $data['permanent']['state'],
            ]);
            $user->addresses()->create([
                'type' => 'current',
                'line1' => $data['current']['line1'],
                'line2' => $data['current']['line2'] ?? null,
                'city' => $data['current']['city'],
                'state' => $data['current']['state'],
            ]);

            // qualifications
            foreach ($data['qualifications'] as $qual) {
                $qual = trim((string)$qual);
                if ($qual !== '') {
                    $user->qualifications()->create(['qualification' => $qual]);
                }
            }

            // experiences
            foreach ($data['experiences'] as $exp) {
                $exp = trim((string)$exp);
                if ($exp !== '') {
                    $user->experiences()->create(['experience' => $exp]);
                }
            }

            DB::commit();

            // redirect to root URL (not route('/'))
            return redirect('/user_login')->with('status', 'Registration successful.');
        } catch (\Throwable $e) {
            DB::rollBack();
            
            // Clean up uploaded file if exists
            if (!empty($profilePath) && Storage::disk('public')->exists($profilePath)) {
                Storage::disk('public')->delete($profilePath);
            }

            // FIX: Return the actual error message to the view
            return back()->withInput()->withErrors($e->getMessage());
        }
    }
}
