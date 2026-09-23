@extends('layouts.app')
@section('title', 'Register - Leave Management System')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4f46e5 100%)">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle mb-3" style="width:64px;height:64px">
                        <i class="bi bi-calendar2-check text-primary" style="font-size:1.75rem"></i>
                    </div>
                    <h3 class="text-white fw-bold">Create Account</h3>
                    <p class="text-white-50 small">Register to start managing your leaves</p>
                </div>
                <div class="card border-0 shadow-lg" style="border-radius:16px">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" placeholder="John Doe" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="name@company.com" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone (Optional)</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+1 234 567 890">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                <i class="bi bi-person-plus me-2"></i>Create Account
                            </button>
                        </form>
                        <hr class="my-3">
                        <p class="text-center mb-0 small">
                            Already have an account? <a href="{{ route('login') }}" class="fw-semibold">Sign in</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
