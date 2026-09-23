@extends('layouts.app')
@section('title', 'Employees')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h2>Employees</h2>
        <p>Manage all employees</p>
    </div>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Add Employee</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.employees.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Name, email, or ID...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Department</label>
                <select name="department_id" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search me-1"></i>Search</button>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Employee ID</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Join Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar" style="width:32px;height:32px;font-size:0.7rem">{{ strtoupper(substr($emp->name, 0, 1)) }}</div>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.85rem">{{ $emp->name }}</div>
                                    <div class="text-muted" style="font-size:0.75rem">{{ $emp->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td><code>{{ $emp->employee_id ?? 'N/A' }}</code></td>
                        <td>{{ $emp->department->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-{{ $emp->role === 'manager' ? 'info' : 'secondary' }}">{{ ucfirst($emp->role) }}</span></td>
                        <td style="font-size:0.8rem">{{ $emp->join_date ? $emp->join_date->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $emp->is_active ? 'success' : 'danger' }}">{{ $emp->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.employees.show', $emp) }}" class="btn btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.employees.edit', $emp) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.employees.toggle-status', $emp) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-{{ $emp->is_active ? 'warning' : 'success' }}" title="{{ $emp->is_active ? 'Deactivate' : 'Activate' }}" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-{{ $emp->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $employees->withQueryString()->links() }}</div>
@endsection
