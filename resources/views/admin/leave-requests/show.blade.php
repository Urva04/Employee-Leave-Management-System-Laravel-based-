@extends('layouts.app')
@section('title', 'Leave Request Details')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-2 mb-2">
        <a href="{{ route('admin.leave-requests.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h2 class="mb-0">Leave Request #{{ $leaveRequest->id }}</h2>
        <span class="badge bg-{{ $leaveRequest->status_badge }} ms-2">{{ ucfirst($leaveRequest->status) }}</span>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Request Details</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted small">Employee</div>
                        <div class="fw-semibold">{{ $leaveRequest->user->name }}</div>
                        <div class="text-muted small">{{ $leaveRequest->user->employee_id }} &middot; {{ $leaveRequest->user->department->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Leave Type</div>
                        <div class="fw-semibold">{{ $leaveRequest->leaveType->name }}</div>
                        <div class="text-muted small">{{ $leaveRequest->leaveType->is_paid ? 'Paid' : 'Unpaid' }}</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small">Start Date</div>
                        <div class="fw-semibold">{{ $leaveRequest->start_date->format('M d, Y') }}</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small">End Date</div>
                        <div class="fw-semibold">{{ $leaveRequest->end_date->format('M d, Y') }}</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small">Total Days</div>
                        <div class="fw-semibold">{{ $leaveRequest->total_days }} day(s)</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Reason</div>
                        <div class="fw-normal">{{ $leaveRequest->reason }}</div>
                    </div>
                    @if($leaveRequest->admin_remarks)
                    <div class="col-12">
                        <div class="text-muted small">Admin Remarks</div>
                        <div class="fw-normal">{{ $leaveRequest->admin_remarks }}</div>
                    </div>
                    @endif
                    @if($leaveRequest->approver)
                    <div class="col-sm-6">
                        <div class="text-muted small">Processed By</div>
                        <div class="fw-semibold">{{ $leaveRequest->approver->name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Processed At</div>
                        <div class="fw-semibold">{{ $leaveRequest->approved_at->format('M d, Y h:i A') }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if($leaveRequest->status === 'pending')
        <!-- Approve -->
        <div class="card mb-3">
            <div class="card-header bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle me-2"></i>Approve Request</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.leave-requests.approve', $leaveRequest) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Remarks (Optional)</label>
                        <textarea name="admin_remarks" class="form-control" rows="3" placeholder="Add remarks..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this leave request?')">
                        <i class="bi bi-check-lg me-1"></i> Approve
                    </button>
                </form>
            </div>
        </div>
        <!-- Reject -->
        <div class="card">
            <div class="card-header bg-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle me-2"></i>Reject Request</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.leave-requests.reject', $leaveRequest) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea name="admin_remarks" class="form-control @error('admin_remarks') is-invalid @enderror" rows="3" placeholder="Provide reason..." required></textarea>
                        @error('admin_remarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this leave request?')">
                        <i class="bi bi-x-lg me-1"></i> Reject
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body text-center py-4">
                <i class="bi bi-check-circle text-{{ $leaveRequest->status_badge }}" style="font-size:2.5rem"></i>
                <div class="mt-2 fw-semibold">Request {{ ucfirst($leaveRequest->status) }}</div>
                @if($leaveRequest->approver)
                    <div class="text-muted small">By {{ $leaveRequest->approver->name }} on {{ $leaveRequest->approved_at->format('M d, Y') }}</div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
