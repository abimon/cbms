@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">My Profile</h1>
            <p class="page-subtitle">Manage your account settings</p>
        </div>
    </div>

    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" 
                                 class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                        @else
                            <div class="bg-{{ auth()->user()->role == 'SuperAdmin' ? 'danger' : (auth()->user()->role == 'Admin' ? 'primary' : 'secondary') }} bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                <i class="bi bi-person-fill fs-1 text-{{ auth()->user()->role == 'SuperAdmin' ? 'danger' : (auth()->user()->role == 'Admin' ? 'primary' : 'secondary') }}"></i>
                            </div>
                        @endif
                    </div>
                    <h4>{{ auth()->user()->name }}</h4>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    @switch(auth()->user()->role)
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
                    @endswitch
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            <i class="bi bi-house me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-header">
                    <span><i class="bi bi-graph-up me-2"></i>Account Info</span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Member Since</span>
                        <strong>{{ \Carbon\Carbon::parse(auth()->user()->created_at)->format('M d, Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Email Verified</span>
                        @if(auth()->user()->is_verified)
                            <span class="badge-status badge-available"><i class="bi bi-check-circle me-1"></i>Verified</span>
                        @else
                            <span class="badge-status badge-pending"><i class="bi bi-clock me-1"></i>Pending</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between mb-0">
                        <span class="text-muted">Phone</span>
                        <strong>{{ auth()->user()->phone ?? 'Not set' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                                <i class="bi bi-person me-2"></i>Profile
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                                <i class="bi bi-key me-2"></i>Password
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                                <i class="bi bi-bell me-2"></i>Notifications
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="profileTabsContent">
                        
                        <!-- Profile Tab -->
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" tabindex="0">
                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                @if(session('success') && session('tab') == 'profile')
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <label for="avatar" class="form-label">Profile Photo</label>
                                    <div class="d-flex align-items-center gap-3">
                                        @if(auth()->user()->avatar)
                                            <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="Avatar" 
                                                 class="rounded-circle" width="80" height="80" style="object-fit: cover;">
                                        @else
                                            <div class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                <i class="bi bi-person-fill fs-3 text-secondary"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                            <small class="text-muted">JPG, PNG up to 2MB</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                        @error('name')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                        @error('email')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                           placeholder="e.g., +1234567890">
                                    @error('phone')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-0">
                                    <label class="form-label text-muted">Role</label>
                                    <p class="form-control-plaintext">
                                        @switch(auth()->user()->role)
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
                                        @endswitch
                                    </p>
                                </div>

                                <hr class="my-4">

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-2"></i>Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Password Tab -->
                        <div class="tab-pane fade" id="password" role="tabpanel" tabindex="0">
                            <form method="POST" action="{{ route('profile.password') }}">
                                @csrf
                                @method('PUT')
                                
                                @if(session('success') && session('tab') == 'password')
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <label for="current_password" class="form-label">Current Password *</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                               id="current_password" name="current_password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                            <i class="bi bi-eye" id="toggle-current_password"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="new_password" class="form-label">New Password *</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                               id="new_password" name="new_password" required minlength="8">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                            <i class="bi bi-eye" id="toggle-new_password"></i>
                                        </button>
                                    </div>
                                    @error('new_password')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimum 8 characters</small>
                                </div>

                                <div class="mb-4">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password *</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" 
                                               id="new_password_confirmation" name="new_password_confirmation" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')">
                                            <i class="bi bi-eye" id="toggle-new_password_confirmation"></i>
                                        </button>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-key me-2"></i>Update Password
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Notifications Tab -->
                        <div class="tab-pane fade" id="notifications" role="tabpanel" tabindex="0">
                            <form method="POST" action="{{ route('profile.notifications') }}">
                                @csrf
                                @method('PUT')
                                
                                @if(session('success') && session('tab') == 'notifications')
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Manage how you receive updates and notifications from the blood management system.
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch custom-switch">
                                        <input class="form-check-input" type="checkbox" id="receive_emails" name="receive_emails" 
                                               {{ auth()->user()->receive_emails ? 'checked' : '' }}>
                                        <label class="form-check-label" for="receive_emails">
                                            <strong><i class="bi bi-envelope me-2"></i>Email Notifications</strong>
                                            <p class="text-muted small mb-0">Receive important updates, alerts, and system notifications via email</p>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch custom-switch">
                                        <input class="form-check-input" type="checkbox" id="receive_notifications" name="receive_notifications" 
                                               {{ auth()->user()->receive_notifications ? 'checked' : '' }}>
                                        <label class="form-check-label" for="receive_notifications">
                                            <strong><i class="bi bi-bell me-2"></i>In-App Notifications</strong>
                                            <p class="text-muted small mb-0">Receive notifications within the application dashboard</p>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch custom-switch">
                                        <input class="form-check-input" type="checkbox" id="sms_notifications" name="sms_notifications" 
                                               {{ auth()->user()->sms_notifications ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sms_notifications">
                                            <strong><i class="bi bi-chat-dots me-2"></i>SMS Notifications</strong>
                                            <p class="text-muted small mb-0">Receive urgent alerts and critical notifications via SMS</p>
                                        </label>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <i class="bi bi-shield-check me-1"></i>
                                        Your privacy is important. We never share your contact information.
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-2"></i>Save Preferences
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.custom-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
    cursor: pointer;
}
.custom-switch .form-check-label {
    cursor: pointer;
}
</style>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById('toggle-' + fieldId);
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

// Handle tab persistence
document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash;
    if (hash) {
        const tab = document.querySelector(hash + '-tab');
        if (tab) {
            const tabInstance = new bootstrap.Tab(tab);
            tabInstance.show();
        }
    }
});
</script>
@endsection