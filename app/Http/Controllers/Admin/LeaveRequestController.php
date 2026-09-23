<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use App\Services\LeaveBalanceService;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    protected LeaveBalanceService $balanceService;

    public function __construct(LeaveBalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    public function index(Request $request)
    {
        $query = LeaveRequest::with(['user', 'leaveType', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }

        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(15);
        $leaveTypes = LeaveType::where('is_active', true)->get();
        $employees = User::where('role', '!=', 'admin')->where('is_active', true)->get();

        return view('admin.leave-requests.index', compact('leaveRequests', 'leaveTypes', 'employees'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['user.department', 'leaveType', 'approver']);
        return view('admin.leave-requests.show', compact('leaveRequest'));
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $request->validate([
            'admin_remarks' => 'nullable|string|max:500',
        ]);

        $leaveRequest->update([
            'status' => 'approved',
            'admin_remarks' => $request->admin_remarks,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $this->balanceService->approveDays($leaveRequest);

        return back()->with('success', 'Leave request approved successfully.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $request->validate([
            'admin_remarks' => 'required|string|max:500',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'admin_remarks' => $request->admin_remarks,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $this->balanceService->removePendingDays($leaveRequest);

        return back()->with('success', 'Leave request rejected.');
    }
}
