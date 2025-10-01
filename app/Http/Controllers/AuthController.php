<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $incomingData = $request->validate([
            'email' => ['required', Rule::unique('accounts_master', 'email')],
            'username' => ['required', Rule::unique('accounts_master', 'username')],
            'password' => ['required', 'min:6', 'max:20'],
            'first_name' => ['required'],
            'middle_name' => ['nullable'],
            'last_name' => ['required'],
            'contact_no' => ['required'],
            'sex' => ['required']
        ]);

        $user = User::create([
            'email' => $incomingData['email'],
            'username' => $incomingData['username'],
            'password' => bcrypt($incomingData['password']),
            'identifier' => 3,
            'role' => 'user'
        ]);

        $user->profile()->create([
            'fname' => $incomingData['first_name'],
            'mname' => $incomingData['middle_name'] ?? null,
            'lname' => $incomingData['last_name'],
            'contact_no' => $incomingData['contact_no'],
            'sex' => $incomingData['sex'],
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

            if ($user->identifier == 3) {
                $request->session()->regenerate();
                return redirect()->route('home');
            }
            
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

