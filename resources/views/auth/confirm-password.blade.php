@extends('layouts.app')
@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <i class="bi bi-droplet-fill fs-1 mb-3"></i>
            <h2>Reset Password</h2>
            <p>Centralized Blood Management System</p>
            <p> {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</p>
        </div>
        <div class="auth-body">
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password"
                            placeholder="Password">
                    </div>
                    @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        {{ __('Confirm') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection