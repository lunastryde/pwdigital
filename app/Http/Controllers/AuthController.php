<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $incomingData = $request->validate([
            'email' => ['required', Rule::unique('accounts_master', 'email')],
            'username' => ['required', Rule::unique('accounts_master', 'username')],
            'password' => ['required', 'min:6', 'max:20']
        ]);
        
        $incomingData['password'] = bcrypt($incomingData['password']);
        $incomingData['identifier'] = 3;
        $incomingData['role'] = 'user';
        User::create($incomingData);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
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

