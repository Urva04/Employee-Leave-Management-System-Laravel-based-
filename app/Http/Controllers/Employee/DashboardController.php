<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $balances = LeaveBalance::with('leaveType')
            ->where('user_id', $user->id)
            ->where('year', now()->year)
            ->get();

        $pendingCount = LeaveRequest::where('user_id', $user->id)->where('status', 'pending')->count();
        $approvedCount = LeaveRequest::where('user_id', $user->id)->where('status', 'approved')->whereYear('start_date', now()->year)->count();
        $rejectedCount = LeaveRequest::where('user_id', $user->id)->where('status', 'rejected')->whereYear('start_date', now()->year)->count();

        $recentRequests = LeaveRequest::with('leaveType')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $upcomingLeaves = LeaveRequest::with('leaveType')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return view('employee.dashboard', compact(
            'balances', 'pendingCount', 'approvedCount', 'rejectedCount',
            'recentRequests', 'upcomingLeaves'
        ));
    }
}
