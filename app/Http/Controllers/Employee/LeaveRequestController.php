<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
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
        $query = LeaveRequest::with('leaveType')
            ->where('user_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        }

        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(10);
        $leaveTypes = LeaveType::where('is_active', true)->get();

        return view('employee.leave-requests.index', compact('leaveRequests', 'leaveTypes'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::where('is_active', true)->get();
        return view('employee.leave-requests.create', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        $startDate = new \DateTime($validated['start_date']);
        $endDate = new \DateTime($validated['end_date']);
        $totalDays = $this->balanceService->calculateBusinessDays($startDate, $endDate);

        if ($totalDays <= 0) {
            return back()->withInput()->withErrors(['end_date' => 'Selected dates have no business days.']);
        }

        if (!$this->balanceService->hasSufficientBalance(auth()->user(), $validated['leave_type_id'], $totalDays)) {
            return back()->withInput()->withErrors(['leave_type_id' => 'Insufficient leave balance.']);
        }

        // Check for overlapping requests
        $overlap = LeaveRequest::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                  ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('start_date', '<=', $validated['start_date'])
                         ->where('end_date', '>=', $validated['end_date']);
                  });
            })
            ->exists();

        if ($overlap) {
            return back()->withInput()->withErrors(['start_date' => 'You have an overlapping leave request for these dates.']);
        }

        $leaveRequest = LeaveRequest::create([
            'user_id' => auth()->id(),
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        $this->balanceService->addPendingDays($leaveRequest);

        return redirect()->route('employee.leave-requests.index')->with('success', 'Leave request submitted successfully.');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->user_id !== auth()->id()) {
            abort(403);
        }

        $leaveRequest->load(['leaveType', 'approver']);
        return view('employee.leave-requests.show', compact('leaveRequest'));
    }

    public function cancel(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->user_id !== auth()->id()) {
            abort(403);
        }

        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }

        $leaveRequest->update(['status' => 'cancelled']);
        $this->balanceService->removePendingDays($leaveRequest);

        return back()->with('success', 'Leave request cancelled successfully.');
    }

    public function getBalance(Request $request)
    {
        $leaveTypeId = $request->leave_type_id;
        $balance = auth()->user()->leaveBalances()
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', now()->year)
            ->first();

        return response()->json([
            'total' => $balance ? $balance->total_days : 0,
            'used' => $balance ? $balance->used_days : 0,
            'pending' => $balance ? $balance->pending_days : 0,
            'available' => $balance ? $balance->available_days : 0,
        ]);
    }
}
