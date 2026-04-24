@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Withdrawals</h1>
            <p class="page-subtitle">Manage blood withdrawal requests</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card warning">
                <i class="bi bi-hourglass-split stat-icon"></i>
                <div class="stat-value">{{ $withdrawals->where('status', 'requested')->count() }}</div>
                <div class="stat-label">Requested</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <i class="bi bi-check-all stat-icon"></i>
                <div class="stat-value">{{ $withdrawals->where('status', 'approved')->count() }}</div>
                <div class="stat-label">Approved</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <i class="bi bi-check-circle stat-icon"></i>
                <div class="stat-value">{{ $withdrawals->where('status', 'completed')->count() }}</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card primary">
                <i class="bi bi-arrow-down-circle stat-icon"></i>
                <div class="stat-value">{{ $withdrawals->count() }}</div>
                <div class="stat-label">Total Withdrawals</div>
            </div>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-ul me-2"></i>Withdrawal Requests</span>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i></button>
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i></button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Blood Bag</th>
                            <th>Requested By</th>
                            <th>Request Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                        <tr>
                            <td><strong>#WTH-{{ str_pad($withdrawal->id, 3, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>
                                @if($withdrawal->bloodbag)
                                    <span class="blood-type blood-type-{{ strtolower($withdrawal->bloodbag->blood_type ?? 'o') }}">
                                        {{ $withdrawal->bloodbag->blood_type ?? 'N/A' }}
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($withdrawal->user)
                                    <i class="bi bi-person text-muted me-1"></i>
                                    {{ $withdrawal->user->name }}
                                @else
                                    <span class="text-muted">Unknown</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($withdrawal->created_at)->format('M d, Y') }}</td>
                            <td>
                                @switch($withdrawal->status)
                                    @case('requested')
                                        <span class="badge-status badge-pending">Requested</span>
                                        @break
                                    @case('approved')
                                        <span class="badge-status badge-approved">Approved</span>
                                        @break
                                    @case('completed')
                                        <span class="badge-status badge-fulfilled">Completed</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge-status badge-rejected">Rejected</span>
                                        @break
                                    @default
                                        <span class="badge-status badge-pending">{{ $withdrawal->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2"></i>View</a></li>
                                        @if($withdrawal->status == 'requested')
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('withdrawals.update', $withdrawal->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="dropdown-item text-success"><i class="bi bi-check-lg me-2"></i>Approve</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('withdrawals.update', $withdrawal->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-x-lg me-2"></i>Reject</button>
                                            </form>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mt-2">No withdrawal requests found</p>
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