@extends('layouts.app')
@section('title', 'Leave Requests')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h2>Leave Requests</h2>
        <p>Manage all employee leave requests</p>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.leave-requests.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['pending','approved','rejected','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Leave Type</label>
                <select name="leave_type_id" class="form-select">
                    <option value="">All Types</option>
                    @foreach($leaveTypes as $type)
                        <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Employee</label>
                <select name="employee_id" class="form-select">
                    <option value="">All Employees</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('admin.leave-requests.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Period</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Applied On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveRequests as $req)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar" style="width:32px;height:32px;font-size:0.7rem">{{ strtoupper(substr($req->user->name, 0, 1)) }}</div>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.85rem">{{ $req->user->name }}</div>
                                    <div class="text-muted" style="font-size:0.75rem">{{ $req->user->employee_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $req->leaveType->name }}</span></td>
                        <td style="font-size:0.8rem">{{ $req->start_date->format('M d') }} - {{ $req->end_date->format('M d, Y') }}</td>
                        <td>{{ $req->total_days }}</td>
                        <td><span class="badge bg-{{ $req->status_badge }}">{{ ucfirst($req->status) }}</span></td>
                        <td style="font-size:0.8rem">{{ $req->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.leave-requests.show', $req) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No leave requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $leaveRequests->withQueryString()->links() }}</div>
@endsection
