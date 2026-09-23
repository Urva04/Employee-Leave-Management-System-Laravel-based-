<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use App\Services\LeaveBalanceService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Departments
        $departments = [
            ['name' => 'Human Resources', 'code' => 'HR', 'description' => 'Human Resources Department'],
            ['name' => 'Engineering', 'code' => 'ENG', 'description' => 'Software Engineering Department'],
            ['name' => 'Marketing', 'code' => 'MKT', 'description' => 'Marketing & Communications'],
            ['name' => 'Finance', 'code' => 'FIN', 'description' => 'Finance & Accounting'],
            ['name' => 'Operations', 'code' => 'OPS', 'description' => 'Business Operations'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Create Leave Types
        $leaveTypes = [
            ['name' => 'Annual Leave', 'code' => 'AL', 'description' => 'Paid annual vacation leave', 'days_per_year' => 20, 'is_paid' => true],
            ['name' => 'Sick Leave', 'code' => 'SL', 'description' => 'Medical or health related leave', 'days_per_year' => 12, 'is_paid' => true, 'requires_attachment' => true],
            ['name' => 'Personal Leave', 'code' => 'PL', 'description' => 'Personal matters or emergencies', 'days_per_year' => 5, 'is_paid' => true],
            ['name' => 'Maternity Leave', 'code' => 'ML', 'description' => 'Maternity leave for expecting mothers', 'days_per_year' => 90, 'is_paid' => true],
            ['name' => 'Paternity Leave', 'code' => 'PTL', 'description' => 'Paternity leave for new fathers', 'days_per_year' => 10, 'is_paid' => true],
            ['name' => 'Unpaid Leave', 'code' => 'UL', 'description' => 'Leave without pay', 'days_per_year' => 30, 'is_paid' => false],
        ];

        foreach ($leaveTypes as $lt) {
            LeaveType::create($lt);
        }

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@lms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department_id' => 1,
            'employee_id' => 'EMP00001',
            'phone' => '+1 555-0100',
            'join_date' => '2023-01-15',
            'is_active' => true,
        ]);

        // Create Manager
        $manager = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'manager@lms.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'department_id' => 2,
            'employee_id' => 'EMP00002',
            'phone' => '+1 555-0101',
            'join_date' => '2023-03-10',
            'is_active' => true,
        ]);

        // Create Employees
        $employees = [
            ['name' => 'John Smith', 'email' => 'john@lms.com', 'department_id' => 2, 'employee_id' => 'EMP00003', 'join_date' => '2023-06-01'],
            ['name' => 'Emily Davis', 'email' => 'emily@lms.com', 'department_id' => 3, 'employee_id' => 'EMP00004', 'join_date' => '2023-08-15'],
            ['name' => 'Michael Brown', 'email' => 'michael@lms.com', 'department_id' => 4, 'employee_id' => 'EMP00005', 'join_date' => '2024-01-10'],
            ['name' => 'Jessica Wilson', 'email' => 'jessica@lms.com', 'department_id' => 5, 'employee_id' => 'EMP00006', 'join_date' => '2024-03-20'],
            ['name' => 'David Lee', 'email' => 'david@lms.com', 'department_id' => 2, 'employee_id' => 'EMP00007', 'join_date' => '2024-05-01'],
            ['name' => 'Amanda Taylor', 'email' => 'amanda@lms.com', 'department_id' => 1, 'employee_id' => 'EMP00008', 'join_date' => '2024-07-15'],
        ];

        $createdEmployees = [];
        foreach ($employees as $emp) {
            $createdEmployees[] = User::create(array_merge($emp, [
                'password' => Hash::make('password'),
                'role' => 'employee',
                'phone' => '+1 555-' . rand(1000, 9999),
                'is_active' => true,
            ]));
        }

        // Initialize leave balances for all users
        $balanceService = new LeaveBalanceService();
        $allUsers = User::all();
        foreach ($allUsers as $user) {
            $balanceService->initializeBalances($user);
        }

        // Create sample leave requests
        $sampleRequests = [
            // Approved requests
            [
                'user_id' => $createdEmployees[0]->id,
                'leave_type_id' => 1,
                'start_date' => '2026-02-10',
                'end_date' => '2026-02-14',
                'total_days' => 5,
                'status' => 'approved',
                'reason' => 'Family vacation trip planned.',
                'admin_remarks' => 'Approved. Enjoy your vacation!',
                'approved_by' => $admin->id,
                'approved_at' => '2026-02-05 10:00:00',
            ],
            [
                'user_id' => $createdEmployees[1]->id,
                'leave_type_id' => 2,
                'start_date' => '2026-03-03',
                'end_date' => '2026-03-04',
                'total_days' => 2,
                'status' => 'approved',
                'reason' => 'Doctor appointment and recovery.',
                'admin_remarks' => 'Get well soon!',
                'approved_by' => $manager->id,
                'approved_at' => '2026-03-01 09:00:00',
            ],
            // Pending requests
            [
                'user_id' => $createdEmployees[2]->id,
                'leave_type_id' => 1,
                'start_date' => '2026-03-24',
                'end_date' => '2026-03-28',
                'total_days' => 5,
                'status' => 'pending',
                'reason' => 'Need to attend a family event out of town.',
            ],
            [
                'user_id' => $createdEmployees[3]->id,
                'leave_type_id' => 3,
                'start_date' => '2026-03-20',
                'end_date' => '2026-03-20',
                'total_days' => 1,
                'status' => 'pending',
                'reason' => 'Personal errands to attend to.',
            ],
            [
                'user_id' => $createdEmployees[0]->id,
                'leave_type_id' => 2,
                'start_date' => '2026-04-07',
                'end_date' => '2026-04-09',
                'total_days' => 3,
                'status' => 'pending',
                'reason' => 'Scheduled medical procedure.',
            ],
            // Rejected request
            [
                'user_id' => $createdEmployees[4]->id,
                'leave_type_id' => 1,
                'start_date' => '2026-03-10',
                'end_date' => '2026-03-14',
                'total_days' => 5,
                'status' => 'rejected',
                'reason' => 'Want to take a short trip.',
                'admin_remarks' => 'Project deadline conflict. Please reschedule.',
                'approved_by' => $admin->id,
                'approved_at' => '2026-03-08 14:00:00',
            ],
        ];

        foreach ($sampleRequests as $req) {
            $leaveRequest = LeaveRequest::create($req);

            // Update balances accordingly
            if ($leaveRequest->status === 'approved') {
                $balance = LeaveBalance::where('user_id', $leaveRequest->user_id)
                    ->where('leave_type_id', $leaveRequest->leave_type_id)
                    ->where('year', now()->year)
                    ->first();
                if ($balance) {
                    $balance->increment('used_days', $leaveRequest->total_days);
                }
            } elseif ($leaveRequest->status === 'pending') {
                $balance = LeaveBalance::where('user_id', $leaveRequest->user_id)
                    ->where('leave_type_id', $leaveRequest->leave_type_id)
                    ->where('year', now()->year)
                    ->first();
                if ($balance) {
                    $balance->increment('pending_days', $leaveRequest->total_days);
                }
            }
        }
    }
}
