<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// Login routes
      // Show login form
Route::get('/login', [AuthController::class, 'index'])->name('login');
      // Handle login submission
Route::post('/login', [AuthController::class, 'loginUser']); 

// Registration routes
      // Show registration form
Route::get('/register', [AuthController::class, 'create'])->name('register');
      // Handle registration submission
Route::post('/register', [AuthController::class, 'store']);

// Admin and customer dashboard routes
Route::get('/admin/admin_dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
Route::get('/customer/dashboard', [AuthController::class, 'custDashboard'])->name('customer.dashboard');

// Logout route
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

// Route::get('/', [AuthController::class, 'backIndex'])->name('index');