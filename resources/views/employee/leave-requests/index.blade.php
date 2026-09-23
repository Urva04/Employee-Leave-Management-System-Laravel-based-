@extends('layouts.app')
@section('title', 'My Leave Requests')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h2>My Leave Requests</h2>
        <p>View and manage your leave applications</p>
    </div>
    <a href="{{ route('employee.leave-requests.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Apply Leave</a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('employee.leave-requests.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['pending','approved','rejected','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Year</label>
                <select name="year" class="form-select">
                    <option value="">All Years</option>
                    @for($y = now()->year; $y >= now()->year - 2; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('employee.leave-requests.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Leave Type</th>
                        <th>Period</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Applied On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveRequests as $req)
                    <tr>
                        <td><span class="badge bg-light text-dark">{{ $req->leaveType->name }}</span></td>
                        <td style="font-size:0.8rem">{{ $req->start_date->format('M d') }} - {{ $req->end_date->format('M d, Y') }}</td>
                        <td class="fw-semibold">{{ $req->total_days }}</td>
                        <td class="text-muted" style="font-size:0.8rem">{{ Str::limit($req->reason, 40) }}</td>
                        <td><span class="badge bg-{{ $req->status_badge }}">{{ ucfirst($req->status) }}</span></td>
                        <td style="font-size:0.8rem">{{ $req->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('employee.leave-requests.show', $req) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                @if($req->status === 'pending')
                                <form method="POST" action="{{ route('employee.leave-requests.cancel', $req) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Cancel this request?')" title="Cancel">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
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
