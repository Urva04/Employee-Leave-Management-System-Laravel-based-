@extends('layouts.app')
@section('title', 'Edit Employee')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-2 mb-2">
        <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h2 class="mb-0">Edit Employee</h2>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.employees.update', $employee) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $employee->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $employee->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="employee" {{ old('role', $employee->role) == 'employee' ? 'selected' : '' }}>Employee</option>
                                <option value="manager" {{ old('role', $employee->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department <span class="text-danger">*</span></label>
                            <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Join Date <span class="text-danger">*</span></label>
                            <input type="date" name="join_date" class="form-control @error('join_date') is-invalid @enderror" value="{{ old('join_date', $employee->join_date?->format('Y-m-d')) }}" required>
                            @error('join_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', $employee->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Employee</button>
                        <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
