@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">User Profile</h1>
            <p class="page-subtitle">View user details</p>
        </div>
        <a href="{{ route('system-users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Users
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($user->avatar)
                            <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" width="120" height="120">
                        @else
                            <div class="bg-{{ $user->role == 'SuperAdmin' ? 'danger' : ($user->role == 'Admin' ? 'primary' : 'secondary') }} bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                <i class="bi bi-person-fill fs-1 text-{{ $user->role == 'SuperAdmin' ? 'danger' : ($user->role == 'Admin' ? 'primary' : 'secondary') }}"></i>
                            </div>
                        @endif
                    </div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    @switch($user->role)
                        @case('SuperAdmin')
                            <span class="badge bg-danger">SuperAdmin</span>
                            @break
                        @case('Admin')
                            <span class="badge bg-primary">Admin</span>
                            @break
                        @case('Sub-Admin')
                            <span class="badge bg-info">Sub-Admin</span>
                            @break
                        @case('Doctor')
                            <span class="badge bg-success">Doctor</span>
                            @break
                        @case('Donor')
                            <span class="badge bg-warning text-dark">Donor</span>
                            @break
                        @case('Guest')
                            <span class="badge bg-secondary">Guest</span>
                            @break
                        @default
                            <span class="badge bg-light text-dark">{{ $user->role }}</span>
                    @endswitch
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-person-lines-fill me-2"></i>User Information</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Full Name</label>
                            <p class="mb-0 fw-bold">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Email Address</label>
                            <p class="mb-0 fw-bold">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Phone Number</label>
                            <p class="mb-0 fw-bold">{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Role</label>
                            <p class="mb-0 fw-bold">{{ $user->role }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Email Verified</label>
                            <p class="mb-0">
                                @if($user->is_verified)
                                    <span class="badge-status badge-available"><i class="bi bi-check-circle me-1"></i>Verified</span>
                                @else
                                    <span class="badge-status badge-pending"><i class="bi bi-clock me-1"></i>Pending</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Member Since</label>
                            <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($user->created_at)->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('system-users.edit', $user->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Edit User
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection