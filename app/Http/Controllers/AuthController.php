<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationOtpMail;

use Illuminate\Support\Str;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Hash;


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

        // Generate 6-digit OTP
        $code = (string) random_int(100000, 999999);

        // Store OTP in email_otps table
        DB::table('email_otps')->insert([
            'email'      => $user->email,
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
            'attempts'   => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send OTP email
        Mail::to($user->email)->send(new RegistrationOtpMail($code));

        // Redirect to OTP verification page instead of login
        return redirect()
            ->route('otp.show', ['email' => $user->email])
            ->with('status', 'Account created. We sent a 6-digit code to your email to verify your account.');
    }

    public function showOtpForm(Request $request)
    {
        $email = $request->query('email');

        return view('auth.verify-otp', [
            'email'  => $email,
            'status' => session('status'),
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'code'  => ['required', 'digits:6'],
        ]);

        // Find latest OTP for this email
        $otp = DB::table('email_otps')
            ->where('email', $data['email'])
            ->orderByDesc('id')
            ->first();

        if (! $otp) {
            return back()->withErrors([
                'code' => 'No OTP request found for this email.',
            ])->withInput();
        }

        // Check expiry
        if (Carbon::parse($otp->expires_at)->isPast()) {
            return back()->withErrors([
                'code' => 'This code has expired. Please request a new one.',
            ])->withInput();
        }

        // Check attempts (max 5)
        if ($otp->attempts >= 5) {
            return back()->withErrors([
                'code' => 'Too many incorrect attempts. Please request a new code.',
            ])->withInput();
        }

        // Compare codes
        if ($otp->code !== $data['code']) {
            DB::table('email_otps')
                ->where('id', $otp->id)
                ->update([
                    'attempts'   => $otp->attempts + 1,
                    'updated_at' => now(),
                ]);

            return back()->withErrors([
                'code' => 'Incorrect code. Please try again.',
            ])->withInput();
        }

        // Code is correct → mark account as verified
        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return back()->withErrors([
                'code' => 'Account not found.',
            ]);
        }

        $user->email_verified_at = now();
        $user->save();

        // Delete used OTP (ONCE)
        DB::table('email_otps')->where('id', $otp->id)->delete();

        // STAFF FLOW: staff is logged in and opened the register page in a popup
        if (auth()->check()) {
            return response()->view('auth.otp-staff-close', [
                'email' => $user->email,
            ]);
        }

        // NORMAL USER FLOW: they registered on their own
        return redirect()
            ->route('login')
            ->with('status', 'Your account has been verified. You can now sign in.');
    }


    public function resendOtp(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'Account not found.',
            ]);
        }

        // Generate new code
        $code = (string) random_int(100000, 999999);

        DB::table('email_otps')->insert([
            'email'      => $user->email,
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
            'attempts'   => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Mail::to($user->email)->send(new RegistrationOtpMail($code));

        return back()->with('status', 'A new code has been sent to your email.');
    }

    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt($credentials)) {
            $user = auth()->user();

            // Block if email not verified
            if (is_null($user->email_verified_at)) {
                auth()->logout();

                return back()->withErrors([
                    'username' => 'Please verify your account using the code sent to your email.',
                ]);
            }

            // Block if account is deactivated
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

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password', [
            'status' => session('status'),
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if email exists in accounts_master
        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'We could not find an account with that email.',
            ])->withInput();
        }

        // Generate token
        $token = Str::random(64);

        // Store or update token
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token'      => $token,
                'created_at' => now(),
            ]
        );

        // Build reset URL
        $resetUrl = url('/reset-password?token=' . urlencode($token) . '&email=' . urlencode($user->email));

        // Send email
        Mail::to($user->email)->send(new ForgotPasswordMail($resetUrl));

        return back()->with('status', 'We have emailed you a password reset link.');
    }

    public function showResetPasswordForm(Request $request)
    {
        $email = $request->query('email');
        $token = $request->query('token');

        return view('auth.reset-password', [
            'email'  => $email,
            'token'  => $token,
            'status' => session('status'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email'                 => ['required', 'email'],
            'token'                 => ['required', 'string'],
            'password'              => ['required', 'min:6', 'max:20', 'confirmed'],
        ]);

        // Find token record
        $record = DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->where('token', $data['token'])
            ->first();

        if (! $record) {
            return back()->withErrors([
                'email' => 'This password reset link is invalid or has already been used.',
            ]);
        }

        // Check expiry (60 minutes)
        $created = Carbon::parse($record->created_at);
        if ($created->copy()->addMinutes(60)->isPast()) {
            return back()->withErrors([
                'email' => 'This password reset link has expired.',
            ]);
        }

        // Update password
        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'Account not found.',
            ]);
        }

        // Update password
        $user->password = Hash::make($data['password']);

        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
        }

        $user->save();

        // Remove token
        DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->delete();

        return redirect()->route('login')->with('status', 'Your password has been reset. You can now sign in.');
    }



    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

