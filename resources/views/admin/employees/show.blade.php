@extends('layouts.app')
@section('title', 'Employee Details')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-2 mb-2">
        <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h2 class="mb-0">{{ $employee->name }}</h2>
        <span class="badge bg-{{ $employee->is_active ? 'success' : 'danger' }}">{{ $employee->is_active ? 'Active' : 'Inactive' }}</span>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div class="avatar mx-auto mb-3" style="width:72px;height:72px;font-size:1.5rem">{{ strtoupper(substr($employee->name, 0, 1)) }}</div>
                <h5 class="fw-bold mb-1">{{ $employee->name }}</h5>
                <div class="text-muted mb-2">{{ $employee->email }}</div>
                <span class="badge bg-{{ $employee->role === 'manager' ? 'info' : 'secondary' }}">{{ ucfirst($employee->role) }}</span>
            </div>
            <hr class="my-0">
            <div class="card-body">
                <div class="row g-2" style="font-size:0.85rem">
                    <div class="col-6 text-muted">Employee ID</div>
                    <div class="col-6 fw-semibold">{{ $employee->employee_id ?? 'N/A' }}</div>
                    <div class="col-6 text-muted">Department</div>
                    <div class="col-6 fw-semibold">{{ $employee->department->name ?? 'N/A' }}</div>
                    <div class="col-6 text-muted">Phone</div>
                    <div class="col-6 fw-semibold">{{ $employee->phone ?? 'N/A' }}</div>
                    <div class="col-6 text-muted">Join Date</div>
                    <div class="col-6 fw-semibold">{{ $employee->join_date?->format('M d, Y') ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-pencil me-1"></i>Edit</a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Leave Balances -->
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-wallet2 me-2"></i>Leave Balances ({{ now()->year }})</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Leave Type</th><th>Total</th><th>Used</th><th>Pending</th><th>Available</th></tr>
                        </thead>
                        <tbody>
                            @forelse($balances as $balance)
                            <tr>
                                <td class="fw-semibold">{{ $balance->leaveType->name }}</td>
                                <td>{{ number_format($balance->total_days, 1) }}</td>
                                <td class="text-danger">{{ number_format($balance->used_days, 1) }}</td>
                                <td class="text-warning">{{ number_format($balance->pending_days, 1) }}</td>
                                <td class="text-success fw-bold">{{ number_format($balance->available_days, 1) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted">No balances found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Leaves -->
        <div class="card">
            <div class="card-header"><i class="bi bi-clock-history me-2"></i>Recent Leave Requests</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Type</th><th>Period</th><th>Days</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentLeaves as $leave)
                            <tr>
                                <td>{{ $leave->leaveType->name }}</td>
                                <td style="font-size:0.8rem">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</td>
                                <td>{{ $leave->total_days }}</td>
                                <td><span class="badge bg-{{ $leave->status_badge }}">{{ ucfirst($leave->status) }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-3 text-muted">No leave requests yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
