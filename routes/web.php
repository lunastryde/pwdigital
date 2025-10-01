<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;

Route::get('/', function () {
    return view('welcome');
});

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

//Logout for User
Route::post('/logout', [AuthController::class, 'logout']);

//Logout for Admin
Route::post('/staff/logout', [AdminAuthController::class, 'logout']);


// ============================================================
//     ================  User Routes   =================
// ============================================================

// User Home
Route::get('/home', function () {
    return view('User.user_base');
})->name('home');

// ============================================================
//  ================  User Plus Icon Routes   ===============
// ============================================================

// ID Application
Route::get('/form/id', function () {
    return view('User.user_form');
})->name('form.id');

// Financial Assistance
Route::get('/form/financial', function () {
    return view('User.user_request_form');
})->name('form.financial');

// Assistive Device
Route::get('/form/device', function () {
    return view('User.user_request_form');
})->name('form.device');

// Booklet
Route::get('/form/booklet', function () {
    return view('User.user_request_form');
})->name('form.booklet');

// ============================================================
//    =================  Admin Routes    =================
// ============================================================

// Admin Home   
Route::get('/staff/home', function () {
    return view('Staff.staff_base');
})->name('staff.home');
