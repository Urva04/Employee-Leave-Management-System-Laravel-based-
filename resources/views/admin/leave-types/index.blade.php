@extends('layouts.app')
@section('title', 'Leave Types')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h2>Leave Types</h2>
        <p>Manage leave type configurations</p>
    </div>
    <a href="{{ route('admin.leave-types.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Add Leave Type</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Days/Year</th>
                        <th>Paid</th>
                        <th>Attachment</th>
                        <th>Requests</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveTypes as $type)
                    <tr>
                        <td class="fw-semibold">{{ $type->name }}</td>
                        <td><code>{{ $type->code }}</code></td>
                        <td>{{ $type->days_per_year }}</td>
                        <td>{!! $type->is_paid ? '<i class="bi bi-check-circle text-success"></i>' : '<i class="bi bi-x-circle text-muted"></i>' !!}</td>
                        <td>{!! $type->requires_attachment ? '<i class="bi bi-check-circle text-success"></i>' : '<i class="bi bi-x-circle text-muted"></i>' !!}</td>
                        <td><span class="badge bg-light text-dark">{{ $type->leave_requests_count }}</span></td>
                        <td><span class="badge bg-{{ $type->is_active ? 'success' : 'danger' }}">{{ $type->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.leave-types.edit', $type) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.leave-types.toggle-status', $type) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-{{ $type->is_active ? 'warning' : 'success' }}" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-{{ $type->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No leave types found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
