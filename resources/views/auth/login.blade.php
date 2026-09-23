@extends('layouts.app')
@section('title', 'Login - Leave Management System')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4f46e5 100%)">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle mb-3" style="width:64px;height:64px">
                        <i class="bi bi-calendar2-check text-primary" style="font-size:1.75rem"></i>
                    </div>
                    <h3 class="text-white fw-bold">Leave Management</h3>
                    <p class="text-white-50 small">Sign in to manage your leaves</p>
                </div>
                <div class="card border-0 shadow-lg" style="border-radius:16px">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" placeholder="name@company.com" required autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 @error('password') is-invalid @enderror"
                                           placeholder="Enter password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label small" for="remember">Remember me</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </button>
                        </form>
                        <hr class="my-3">
                        <p class="text-center mb-0 small">
                            Don't have an account? <a href="{{ route('register') }}" class="fw-semibold">Register here</a>
                        </p>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-white-50">Demo: admin@lms.com / password</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
