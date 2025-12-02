<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminAuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrintIDController;
use App\Http\Controllers\ReportsExportController;
use App\Http\Controllers\ProfileController;
use App\Livewire\UserSupportChat;
use App\Livewire\StaffSupportChat;

// Email OTP's
Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.show');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');

// Password Resrts
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');


// Route::get('/', function () {
//     return view('welcome');
// });

// Redirects anyone hitting "/" straight to "/login"
Route::redirect('/', '/login');

// ============================================================
//    =================  Authentication   =================
// ============================================================

//Register
Route::get('/register', function () {
    return view('Auth.user_registration');
})->name('register');

Route::post('/register', [AuthController::class, 'register']);

//Login for User
Route::get('/login', function () {
    return view('Auth.user_login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

//Login for Admin
Route::get('/staff/login', function () {
    return view('Auth.staff_login');
})->name('staff.login');

Route::post('/staff/login', [AdminAuthController::class, 'login']);

//Login for Super Admin
Route::get('/super-admin/login', function () {
    return view('Auth.super_login');
})->name('super-admin.login');

Route::post('/super-admin/login', [SuperAdminAuthController::class, 'login']);

//Logout for User
Route::post('/logout', [AuthController::class, 'logout']);

//Logout for Admin
Route::post('/staff/logout', [AdminAuthController::class, 'logout']);

Route::post('/super-admin/logout', [SuperAdminAuthController::class, 'logout']);


// ============================================================
//     ================  User Routes   =================
// ============================================================

// User Home
Route::get('/home', function () {
    return view('User.user_base');
})->name('home');

Route::get('/profile/edit', function () {
    return view('User.user_profile');
})->name('profile.edit');


// ============================================================
//  ================  User Plus Icon Routes   ===============
// ============================================================

// ID Application
Route::get('/form/id', function () {
    return view('User.user_form');
})->name('form.id');

// Request Form
Route::get('/form/request', function () {
    return view('User.user_request_form');
})->name('form.request');

//Support Form
Route::get('/form/support', function() {
    return view('User.user_support_form');
})->name('form.support');

// ============================================================
//    =================  Admin Routes    =================
// ============================================================)

// Admin Home   
Route::get('/staff/home', function () {
    return view('Staff.staff_base');
})->name('staff.home');

// Super Admin Home
Route::get('/super-admin/home', function () {
    return view('SuperAdmin.super_base');
})->name('super-admin.home');

// ID Preview
Route::get('/admin/form-personal/{id}/print', [PrintIdController::class, 'print'])
    ->name('admin.form_personal.print');


// ============================================================
//    =================  User Support Chat    =================
// ============================================================)

Route::middleware('auth')->group(function () {
    Route::get('/support/chat', function () {
        return view('support.chat-page');
    })->name('support.chat');
});


// ============================================================
//    =================  Staff Support Chat    =================
// ============================================================)\

Route::middleware('auth')->group(function () {
    Route::get('/staff/support/chat', function () {
        return view('staff.support-chat-page');
    })->name('staff.support.chat');
});

