<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $incomingData = $request->validate([
            'email'       => ['required', 'email', Rule::unique('accounts_master', 'email')],
            'username'    => ['required', Rule::unique('accounts_master', 'username')],
            'password'    => ['required', 'min:6', 'max:20'],

            'first_name'  => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name'   => ['required', 'string', 'max:100'],
            'contact_no'  => ['required', 'string', 'max:50'],
            'sex'         => ['required', 'string', 'max:10'],

            'date_of_birth' => ['required', 'date', 'before:today'],
        ]);

        // Compute age from DOB
        $dob = Carbon::parse($incomingData['date_of_birth']);
        $age = $dob->age;

        $user = User::create([
            'email' => $incomingData['email'],
            'username' => $incomingData['username'],
            'password' => bcrypt($incomingData['password']),
            'identifier' => 3,
            'role' => 'user'
        ]);

        // Create profile snapshot
        $user->profile()->create([
            'fname'        => $incomingData['first_name'],
            'mname'        => $incomingData['middle_name'] ?? null,
            'lname'        => $incomingData['last_name'],
            'contact_no'   => $incomingData['contact_no'],
            'sex'          => $incomingData['sex'],
            'birthdate'    => $incomingData['date_of_birth'],
            'age'          => $age,

            // leave these null, will be filled after ID is finalized
            'pwd_number'     => null,
            'civil_status'   => null,
            'disability_type'=> null,
            'house_no'       => null,
            'street'         => null,
            'barangay'       => null,
            'municipality'   => null,
            'province'       => null,
        ]);

        return redirect()->route('login')->with('status', 'Account created. Please sign in.');
    }

    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt($credentials)) {
            $user = auth()->user();

            // 🔥 BLOCK IF ACCOUNT IS DEACTIVATED
            if (!$user->is_active) {
                auth()->logout();
                return back()->withErrors([
                    'username' => 'Your account has been deactivated. Please contact the administrator.',
                ]);
            }

            // Normal user
            if ($user->identifier == 3) {
                $request->session()->regenerate();
                return redirect()->route('home');
            }

            // Not authorized (only staff/admin allowed here)
            auth()->logout();
            return back()->withErrors([
                'username' => 'You are not authorized to access this system.',
            ]);
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }


    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

