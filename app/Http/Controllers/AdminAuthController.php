<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Validation\Rule; 

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
    
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
    
            // Only allow identifier 1 or 2
            if (in_array($user->identifier, [1, 2])) {
                $request->session()->regenerate();
                return redirect()->route('staff.home');
            }
    
            // If identifier is not allowed, logout and throw error
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
        return redirect()->route('staff.login');
    }
}
