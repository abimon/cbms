@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Blood Storage</h1>
            <p class="page-subtitle">Manage blood bag storage and transfers</p>
        </div>
        <a href="{{ route('storages.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add to Storage
        </a>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card success">
                <i class="bi bi-check-circle stat-icon"></i>
                <div class="stat-value">{{ $bloodbags->where('status', 'available')->count() }}</div>
                <div class="stat-label">Available</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <i class="bi bi-arrow-left-right stat-icon"></i>
                <div class="stat-value">{{ $bloodbags->where('status', 'transfered')->count() }}</div>
                <div class="stat-label">Transferred</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <i class="bi bi-hourglass stat-icon"></i>
                <div class="stat-value">{{ $bloodbags->where('status', 'expired')->count() }}</div>
                <div class="stat-label">Expired</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card primary">
                <i class="bi bi-box-seam stat-icon"></i>
                <div class="stat-value">{{ $bloodbags->count() }}</div>
                <div class="stat-label">Total Stored</div>
            </div>
        </div>
    </div>

    <!-- Storage Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-ul me-2"></i>Blood Storage List</span>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i></button>
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i></button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="white-space: nowrap;min-height: 50vh;">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Blood Bag ID</th>
                            <th>Bank</th>
                            <th>Status</th>
                            <th>Stored Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bloodbags as $bag)
                        <tr>
                            <td><strong>{{ $bag->bloodbag_id }}</strong></td>
                            <td>
                                @if($bag->bank)
                                <i class="bi bi-hospital text-muted me-1"></i>
                                {{ $bag->bank->name }}
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @switch($bag->status)
                                @case('available')
                                <span class="badge-status badge-available">Available</span>
                                @break
                                @case('transfered')
                                <span class="badge-status badge-approved">Transferred</span>
                                @break
                                @case('expired')
                                <span class="badge-status badge-expired">Expired</span>
                                @break
                                @default
                                <span class="badge-status badge-pending">{{ $bag->status }}</span>
                                @endswitch
                            </td>
                            <td>{{ \Carbon\Carbon::parse($bag->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2"></i>View</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mt-2">No blood storage records found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection