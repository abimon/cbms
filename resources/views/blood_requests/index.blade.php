@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Blood Requests</h1>
            <p class="page-subtitle">Manage blood transfusion requests</p>
        </div>
        <a href="{{ route('requests.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>New Request
        </a>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card warning">
                <i class="bi bi-hourglass-split stat-icon"></i>
                <div class="stat-value">{{ $bloodRequests->where('status', 'pending')->count() }}</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <i class="bi bi-check-all stat-icon"></i>
                <div class="stat-value">{{ $bloodRequests->where('status', 'approved')->count() }}</div>
                <div class="stat-label">Approved</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <i class="bi bi-check-circle stat-icon"></i>
                <div class="stat-value">{{ $bloodRequests->where('status', 'fulfilled')->count() }}</div>
                <div class="stat-label">Fulfilled</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card primary">
                <i class="bi bi-droplet stat-icon"></i>
                <div class="stat-value">{{ $bloodRequests->sum('quantity') }}</div>
                <div class="stat-label">Total Units Requested</div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-ul me-2"></i>All Blood Requests</span>
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
                            <th>ID</th>
                            <th>Blood Type</th>
                            <th>Quantity</th>
                            <th>Hospital</th>
                            <th>Contact</th>
                            <th>Request Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bloodRequests as $request)
                        <tr>
                            <td><strong>#REQ-{{ str_pad($request->id, 3, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>
                                @php
                                $typeClass = strtolower(explode('-', $request->blood_type)[0]);
                                @endphp
                                <span class="blood-type blood-type-{{ $typeClass }}">
                                    {{ $request->blood_type }}
                                </span>
                            </td>
                            <td>{{ $request->quantity }} units</td>
                            <td>{{ $request->hospital }}</td>
                            <td>{{ $request->contact_phone }}</td>
                            <td>{{ \Carbon\Carbon::parse($request->request_date)->format('M d, Y') }}</td>
                            <td>
                                @switch($request->status)
                                @case('pending')
                                <span class="badge-status badge-pending">Pending</span>
                                @break
                                @case('approved')
                                <span class="badge-status badge-approved">Approved</span>
                                @break
                                @case('fulfilled')
                                <span class="badge-status badge-fulfilled">Fulfilled</span>
                                @break
                                @case('rejected')
                                <span class="badge-status badge-rejected">Rejected</span>
                                @break
                                @default
                                <span class="badge-status badge-pending">{{ $request->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('requests.show', $request->id) }}"><i class="bi bi-eye me-2"></i>View</a></li>
                                        <li><a class="dropdown-item" href="{{ route('requests.edit', $request->id) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                        @if($request->status == 'pending')
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('requests.update', $request->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="dropdown-item text-success"><i class="bi bi-check-lg me-2"></i>Approve</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('requests.update', $request->id) }}" method="POST" class="d-inline">
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
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mt-2">No blood requests found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $bloodRequests->links() }}
        </div>
    </div>
</div>
@endsection