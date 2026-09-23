<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Services\LeaveBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('employee.dashboard');
        }
        $departments = Department::where('is_active', true)->get();
        return view('auth.register', compact('departments'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department_id' => 'required|exists:departments,id',
            'phone' => 'nullable|string|max:20',
        ]);

        $lastUser = User::latest('id')->first();
        $employeeId = 'EMP' . str_pad(($lastUser ? $lastUser->id + 1 : 1), 5, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'employee',
            'department_id' => $validated['department_id'],
            'employee_id' => $employeeId,
            'phone' => $validated['phone'] ?? null,
            'join_date' => now(),
            'is_active' => true,
        ]);

        $balanceService = new LeaveBalanceService();
        $balanceService->initializeBalances($user);

        Auth::login($user);

        return redirect()->route('employee.dashboard');
    }
}
