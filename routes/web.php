<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});


//Register
Route::get('/register', function () {
    return view('Auth.user_registration');
})->name('register');

Route::post('/register', [AuthController::class, 'register']);

//Login
Route::get('/login', function () {
    return view('Auth.user_login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

//Logout
Route::post('/logout', [AuthController::class, 'logout']);


//User Home
Route::get('/home/profile', function () {
    return view('User.user_base');
})->name('home.profile');

//Plus Icon Routes

