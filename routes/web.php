<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LeaveRequestController as AdminLeaveRequestController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LeaveTypeController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\LeaveRequestController as EmployeeLeaveRequestController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin routes
Route::middleware(['auth', 'role:admin,manager'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Leave Requests Management
    Route::get('/leave-requests', [AdminLeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('/leave-requests/{leaveRequest}', [AdminLeaveRequestController::class, 'show'])->name('leave-requests.show');
    Route::post('/leave-requests/{leaveRequest}/approve', [AdminLeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::post('/leave-requests/{leaveRequest}/reject', [AdminLeaveRequestController::class, 'reject'])->name('leave-requests.reject');

    // Employee Management
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::post('/employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');

    // Leave Types Management
    Route::get('/leave-types', [LeaveTypeController::class, 'index'])->name('leave-types.index');
    Route::get('/leave-types/create', [LeaveTypeController::class, 'create'])->name('leave-types.create');
    Route::post('/leave-types', [LeaveTypeController::class, 'store'])->name('leave-types.store');
    Route::get('/leave-types/{leaveType}/edit', [LeaveTypeController::class, 'edit'])->name('leave-types.edit');
    Route::put('/leave-types/{leaveType}', [LeaveTypeController::class, 'update'])->name('leave-types.update');
    Route::post('/leave-types/{leaveType}/toggle-status', [LeaveTypeController::class, 'toggleStatus'])->name('leave-types.toggle-status');

    // Department Management
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::post('/departments/{department}/toggle-status', [DepartmentController::class, 'toggleStatus'])->name('departments.toggle-status');
});

// Employee routes
Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');

    Route::get('/leave-requests', [EmployeeLeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('/leave-requests/create', [EmployeeLeaveRequestController::class, 'create'])->name('leave-requests.create');
    Route::post('/leave-requests', [EmployeeLeaveRequestController::class, 'store'])->name('leave-requests.store');
    Route::get('/leave-requests/{leaveRequest}', [EmployeeLeaveRequestController::class, 'show'])->name('leave-requests.show');
    Route::post('/leave-requests/{leaveRequest}/cancel', [EmployeeLeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
    Route::get('/leave-balance', [EmployeeLeaveRequestController::class, 'getBalance'])->name('leave-balance');
});
