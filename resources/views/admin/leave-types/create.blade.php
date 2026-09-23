@extends('layouts.app')
@section('title', 'Add Leave Type')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-2 mb-2">
        <a href="{{ route('admin.leave-types.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h2 class="mb-0">Add Leave Type</h2>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.leave-types.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Annual Leave" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="e.g. AL" maxlength="10" required>
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Brief description...">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Days Per Year <span class="text-danger">*</span></label>
                            <input type="number" name="days_per_year" class="form-control @error('days_per_year') is-invalid @enderror" value="{{ old('days_per_year', 0) }}" min="0" max="365" required>
                            @error('days_per_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <div class="form-check mt-4">
                                <input type="checkbox" name="is_paid" value="1" class="form-check-input" id="isPaid" {{ old('is_paid', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isPaid">Paid Leave</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check mt-4">
                                <input type="checkbox" name="requires_attachment" value="1" class="form-check-input" id="reqAttach" {{ old('requires_attachment') ? 'checked' : '' }}>
                                <label class="form-check-label" for="reqAttach">Requires Attachment</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Create Leave Type</button>
                        <a href="{{ route('admin.leave-types.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
