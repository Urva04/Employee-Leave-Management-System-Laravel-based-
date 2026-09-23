@extends('layouts.app')
@section('title', 'Departments')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h2>Departments</h2>
        <p>Manage company departments</p>
    </div>
    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Add Department</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Employees</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                    <tr>
                        <td class="fw-semibold">{{ $dept->name }}</td>
                        <td><code>{{ $dept->code }}</code></td>
                        <td class="text-muted" style="font-size:0.85rem">{{ Str::limit($dept->description, 50) ?? '—' }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $dept->active_users_count }}</span></td>
                        <td><span class="badge bg-{{ $dept->is_active ? 'success' : 'danger' }}">{{ $dept->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.departments.edit', $dept) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.departments.toggle-status', $dept) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-{{ $dept->is_active ? 'warning' : 'success' }}" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-{{ $dept->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No departments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
