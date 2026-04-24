@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Users</h1>
            <p class="page-subtitle">Manage system users and roles</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card primary">
                <i class="bi bi-people stat-icon"></i>
                <div class="stat-value">{{ $users->count() }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <i class="bi bi-check-circle stat-icon"></i>
                <div class="stat-value">{{ $users->where('is_verified', 1)->count() }}</div>
                <div class="stat-label">Verified</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <i class="bi bi-shield stat-icon"></i>
                <div class="stat-value">{{ $users->where('role', 'Admin')->count() + $users->where('role', 'SuperAdmin')->count() }}</div>
                <div class="stat-label">Admins</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <i class="bi bi-person-heart stat-icon"></i>
                <div class="stat-value">{{ $users->where('role', 'Donor')->count() }}</div>
                <div class="stat-label">Donors</div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-ul me-2"></i>All Users</span>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i></button>
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i></button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="white-space: nowrap; min-height: 50vh;">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Verified</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-{{ $user->role == 'SuperAdmin' ? 'danger' : ($user->role == 'Admin' ? 'primary' : 'secondary') }} bg-opacity-10 rounded-circle p-2 me-2">
                                        @if($user->avatar)
                                        <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" width="35" height="35">
                                        @else
                                        <i class="bi bi-person-fill text-{{ $user->role == 'SuperAdmin' ? 'danger' : ($user->role == 'Admin' ? 'primary' : 'secondary') }}"></i>
                                        @endif
                                    </div>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? 'N/A' }}</td>
                            <td>
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
                            </td>
                            <td>
                                @if($user->is_verified)
                                <span class="badge-status badge-available"><i class="bi bi-check-circle me-1"></i>Verified</span>
                                @else
                                <span class="badge-status badge-pending"><i class="bi bi-clock me-1"></i>Pending</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('system-users.show', $user->id) }}"><i class="bi bi-eye me-2"></i>View</a></li>
                                        <li><a class="dropdown-item" href="{{ route('system-users.edit', $user->id) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('system-users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="bi bi-trash me-2"></i>Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-people fs-1"></i>
                                <p class="mt-2">No users found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection