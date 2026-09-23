@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h2>My Dashboard</h2>
        <p>Welcome back, {{ auth()->user()->name }}!</p>
    </div>
    <a href="{{ route('employee.leave-requests.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Apply Leave
    </a>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-value text-warning">{{ $pendingCount }}</div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-clock-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Approved This Year</div>
                    <div class="stat-value text-success">{{ $approvedCount }}</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Rejected This Year</div>
                    <div class="stat-value text-danger">{{ $rejectedCount }}</div>
                </div>
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Employee ID</div>
                    <div class="stat-value" style="font-size:1.2rem">{{ auth()->user()->employee_id ?? 'N/A' }}</div>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-person-badge-fill"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Leave Balances -->
<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-wallet2 me-2"></i>Leave Balances ({{ now()->year }})</span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @forelse($balances as $balance)
            <div class="col-sm-6 col-lg-3">
                <div class="border rounded-3 p-3 text-center">
                    <div class="fw-bold text-primary mb-1" style="font-size:0.85rem">{{ $balance->leaveType->name }}</div>
                    <div class="d-flex justify-content-around mt-2" style="font-size:0.8rem">
                        <div>
                            <div class="text-muted">Total</div>
                            <div class="fw-bold">{{ number_format($balance->total_days, 1) }}</div>
                        </div>
                        <div>
                            <div class="text-muted">Used</div>
                            <div class="fw-bold text-danger">{{ number_format($balance->used_days, 1) }}</div>
                        </div>
                        <div>
                            <div class="text-muted">Available</div>
                            <div class="fw-bold text-success">{{ number_format($balance->available_days, 1) }}</div>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height:6px">
                        @php $pct = $balance->total_days > 0 ? (($balance->used_days + $balance->pending_days) / $balance->total_days) * 100 : 0; @endphp
                        <div class="progress-bar bg-primary" style="width:{{ min($pct, 100) }}%"></div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-3">No leave balances initialized yet.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Recent Requests -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock-history me-2"></i>Recent Requests</span>
                <a href="{{ route('employee.leave-requests.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Type</th><th>Period</th><th>Days</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentRequests as $req)
                            <tr>
                                <td>{{ $req->leaveType->name }}</td>
                                <td style="font-size:0.8rem">{{ $req->start_date->format('M d') }} - {{ $req->end_date->format('M d') }}</td>
                                <td>{{ $req->total_days }}</td>
                                <td><span class="badge bg-{{ $req->status_badge }}">{{ ucfirst($req->status) }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">No leave requests yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Leaves -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-calendar-event me-2"></i>Upcoming Approved Leaves</div>
            <div class="card-body">
                @forelse($upcomingLeaves as $leave)
                <div class="d-flex align-items-center gap-3 {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                    <div class="text-center" style="min-width:48px">
                        <div class="fw-bold text-primary" style="font-size:1.1rem">{{ $leave->start_date->format('d') }}</div>
                        <div class="text-muted" style="font-size:0.7rem">{{ $leave->start_date->format('M') }}</div>
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:0.85rem">{{ $leave->leaveType->name }}</div>
                        <div class="text-muted" style="font-size:0.75rem">{{ $leave->total_days }} day(s) &middot; {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">No upcoming leaves.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
