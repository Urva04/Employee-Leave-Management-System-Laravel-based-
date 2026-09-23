<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\LeaveBalance;
use App\Models\User;
use App\Services\LeaveBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('department')->where('role', '!=', 'admin');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $employees = $query->orderBy('name')->paginate(15);
        $departments = Department::where('is_active', true)->get();

        return view('admin.employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('admin.employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:employee,manager',
            'department_id' => 'required|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'join_date' => 'required|date',
        ]);

        $lastUser = User::latest('id')->first();
        $employeeId = 'EMP' . str_pad(($lastUser ? $lastUser->id + 1 : 1), 5, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'department_id' => $validated['department_id'],
            'employee_id' => $employeeId,
            'phone' => $validated['phone'] ?? null,
            'join_date' => $validated['join_date'],
            'is_active' => true,
        ]);

        $balanceService = new LeaveBalanceService();
        $balanceService->initializeBalances($user);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(User $employee)
    {
        $employee->load('department');
        $balances = LeaveBalance::with('leaveType')
            ->where('user_id', $employee->id)
            ->where('year', now()->year)
            ->get();

        $recentLeaves = $employee->leaveRequests()
            ->with('leaveType')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.employees.show', compact('employee', 'balances', 'recentLeaves'));
    }

    public function edit(User $employee)
    {
        $departments = Department::where('is_active', true)->get();
        return view('admin.employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'role' => 'required|in:employee,manager',
            'department_id' => 'required|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'join_date' => 'required|date',
            'is_active' => 'boolean',
        ]);

        $employee->update($validated);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function toggleStatus(User $employee)
    {
        $employee->update(['is_active' => !$employee->is_active]);
        $status = $employee->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Employee {$status} successfully.");
    }
}
