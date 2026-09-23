<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::withCount('leaveRequests')->orderBy('name')->get();
        return view('admin.leave-types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('admin.leave-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:leave_types',
            'description' => 'nullable|string|max:500',
            'days_per_year' => 'required|integer|min:0|max:365',
            'is_paid' => 'boolean',
            'requires_attachment' => 'boolean',
        ]);

        $validated['is_paid'] = $request->boolean('is_paid');
        $validated['requires_attachment'] = $request->boolean('requires_attachment');

        LeaveType::create($validated);

        return redirect()->route('admin.leave-types.index')->with('success', 'Leave type created successfully.');
    }

    public function edit(LeaveType $leaveType)
    {
        return view('admin.leave-types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:leave_types,code,' . $leaveType->id,
            'description' => 'nullable|string|max:500',
            'days_per_year' => 'required|integer|min:0|max:365',
            'is_paid' => 'boolean',
            'requires_attachment' => 'boolean',
        ]);

        $validated['is_paid'] = $request->boolean('is_paid');
        $validated['requires_attachment'] = $request->boolean('requires_attachment');

        $leaveType->update($validated);

        return redirect()->route('admin.leave-types.index')->with('success', 'Leave type updated successfully.');
    }

    public function toggleStatus(LeaveType $leaveType)
    {
        $leaveType->update(['is_active' => !$leaveType->is_active]);
        $status = $leaveType->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Leave type {$status} successfully.");
    }
}
