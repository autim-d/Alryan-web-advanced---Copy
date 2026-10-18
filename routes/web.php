<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Request & Response Lifecycle Teaching Module
|--------------------------------------------------------------------------
*/

// Home & existing routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/mypage', function () {
    return view('mypage');
});
Route::get('/index', [IndexController::class, 'index']);

// -------------------------------------------------------------------------
// Authentication Routes (Demonstrating GET, POST, Request & Response objects)
// -------------------------------------------------------------------------

// 1. Signup / Registration Flow
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'register'])->name('signup.post');

// 2. Login Flow
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// 3. Authenticated Dashboard & Introspection View
Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');

// 4. Logout (POST request for CSRF protection)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// 5. Mock API Endpoint (Demonstrating JSON Responses & Request Payload extraction)
Route::match(['get', 'post'], '/api/check-user', [AuthController::class, 'apiCheckUser'])->name('api.check-user');
