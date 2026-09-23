@extends('layouts.app')
@section('title', 'Leave Request Details')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-2 mb-2">
        <a href="{{ route('employee.leave-requests.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h2 class="mb-0">Leave Request #{{ $leaveRequest->id }}</h2>
        <span class="badge bg-{{ $leaveRequest->status_badge }} ms-2">{{ ucfirst($leaveRequest->status) }}</span>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Request Details</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted small">Leave Type</div>
                        <div class="fw-semibold">{{ $leaveRequest->leaveType->name }}</div>
                        <div class="text-muted small">{{ $leaveRequest->leaveType->is_paid ? 'Paid' : 'Unpaid' }} Leave</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Status</div>
                        <div><span class="badge bg-{{ $leaveRequest->status_badge }}">{{ ucfirst($leaveRequest->status) }}</span></div>
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
                        <div>{{ $leaveRequest->reason }}</div>
                    </div>
                    @if($leaveRequest->admin_remarks)
                    <div class="col-12">
                        <div class="text-muted small">Admin Remarks</div>
                        <div class="alert alert-{{ $leaveRequest->status === 'approved' ? 'success' : 'danger' }} py-2 mb-0">
                            {{ $leaveRequest->admin_remarks }}
                        </div>
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
        <div class="card">
            <div class="card-body text-center py-4">
                @if($leaveRequest->status === 'pending')
                    <i class="bi bi-clock text-warning" style="font-size:2.5rem"></i>
                    <div class="mt-2 fw-semibold">Awaiting Approval</div>
                    <div class="text-muted small mb-3">Your request is being reviewed</div>
                    <form method="POST" action="{{ route('employee.leave-requests.cancel', $leaveRequest) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Cancel this leave request?')">
                            <i class="bi bi-x-lg me-1"></i>Cancel Request
                        </button>
                    </form>
                @elseif($leaveRequest->status === 'approved')
                    <i class="bi bi-check-circle text-success" style="font-size:2.5rem"></i>
                    <div class="mt-2 fw-semibold text-success">Approved</div>
                    <div class="text-muted small">Your leave has been approved</div>
                @elseif($leaveRequest->status === 'rejected')
                    <i class="bi bi-x-circle text-danger" style="font-size:2.5rem"></i>
                    <div class="mt-2 fw-semibold text-danger">Rejected</div>
                    <div class="text-muted small">Your leave request was rejected</div>
                @else
                    <i class="bi bi-dash-circle text-secondary" style="font-size:2.5rem"></i>
                    <div class="mt-2 fw-semibold text-secondary">Cancelled</div>
                    <div class="text-muted small">You cancelled this request</div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="fw-bold mb-2" style="font-size:0.85rem">Timeline</h6>
                <div class="d-flex gap-2 mb-2" style="font-size:0.8rem">
                    <i class="bi bi-circle-fill text-primary" style="font-size:0.5rem;margin-top:6px"></i>
                    <div>
                        <div class="fw-semibold">Submitted</div>
                        <div class="text-muted">{{ $leaveRequest->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                @if($leaveRequest->approved_at)
                <div class="d-flex gap-2" style="font-size:0.8rem">
                    <i class="bi bi-circle-fill text-{{ $leaveRequest->status === 'approved' ? 'success' : 'danger' }}" style="font-size:0.5rem;margin-top:6px"></i>
                    <div>
                        <div class="fw-semibold">{{ ucfirst($leaveRequest->status) }}</div>
                        <div class="text-muted">{{ $leaveRequest->approved_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
