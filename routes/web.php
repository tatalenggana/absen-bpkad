<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;

// Public routes
Route::get('/', function () {
    // Redirect authenticated users to their dashboard
    if (auth()->check()) {
        return auth()->user()->isAdmin() 
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User routes
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [AttendanceController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/profile', [AttendanceController::class, 'showProfile'])->name('user.profile');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkIn');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkOut');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AttendanceController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/attendance/filter-date', [AttendanceController::class, 'filterByDate'])->name('admin.filterDate');
    Route::get('/attendance/filter-month', [AttendanceController::class, 'filterByMonth'])->name('admin.filterMonth');
    Route::get('/attendance-report', [AttendanceController::class, 'attendanceReport'])->name('admin.attendance-report');
    Route::get('/user/{userId}/history', [AttendanceController::class, 'userHistory'])->name('admin.userHistory');
    Route::get('/settings', [AttendanceController::class, 'showSettings'])->name('admin.settings');
    Route::put('/settings/deadline', [AttendanceController::class, 'updateDeadline'])->name('admin.settings.update-deadline');
    Route::put('/settings/location', [AttendanceController::class, 'updateLocation'])->name('admin.settings.update-location');
});
