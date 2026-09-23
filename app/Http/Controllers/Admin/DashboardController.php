<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = User::where('role', '!=', 'admin')->where('is_active', true)->count();
        $pendingRequests = LeaveRequest::where('status', 'pending')->count();
        $approvedToday = LeaveRequest::where('status', 'approved')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();
        $totalDepartments = Department::where('is_active', true)->count();

        $recentRequests = LeaveRequest::with(['user', 'leaveType'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $departmentStats = Department::withCount(['activeUsers'])->where('is_active', true)->get();

        $monthlyStats = LeaveRequest::selectRaw("strftime('%m', start_date) as month, status, count(*) as count")
            ->whereYear('start_date', now()->year)
            ->groupByRaw("strftime('%m', start_date), status")
            ->get()
            ->groupBy('month');

        return view('admin.dashboard', compact(
            'totalEmployees', 'pendingRequests', 'approvedToday',
            'totalDepartments', 'recentRequests', 'departmentStats', 'monthlyStats'
        ));
    }
}
