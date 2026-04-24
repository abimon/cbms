@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Blood Inventory</h1>
            <p class="page-subtitle">Manage blood stock and donations</p>
        </div>
        <a href="{{ route('inventories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add New Blood
        </a>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card primary">
                <i class="bi bi-droplet stat-icon"></i>
                <div class="stat-value">{{ $bloodInventories->count() }}</div>
                <div class="stat-label">Total Blood Bags</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <i class="bi bi-check-circle stat-icon"></i>
                <div class="stat-value">{{ $bloodInventories->where('status', 'tested')->count() }}</div>
                <div class="stat-label">Tested & Ready</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <i class="bi bi-hourglass stat-icon"></i>
                <div class="stat-value">{{ $bloodInventories->where('status', 'not_tested')->count() }}</div>
                <div class="stat-label">Pending Testing</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <i class="bi bi-exclamation-triangle stat-icon"></i>
                <div class="stat-value">{{ $bloodInventories->where('status', 'expired')->count() }}</div>
                <div class="stat-label">Expired</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                
                <div class="col-md-3">
                    <select name="blood_type" class="form-select">
                        <option value="">All Blood Types</option>
                        <option value="A" {{ request('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ request('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="AB" {{ request('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                        <option value="O" {{ request('blood_type') == 'O' ? 'selected' : '' }}>O</option>
                        <option value="NT" {{ request('blood_type') == 'NT' ? 'selected' : '' }}>NT</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="tested" {{ request('status') == 'tested' ? 'selected' : '' }}>Tested</option>
                        <option value="not_tested" {{ request('status') == 'not_tested' ? 'selected' : '' }}>Not Tested</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Used</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="rhesus" class="form-select">
                        <option value="">All Rhesus</option>
                        <option value="Positive" {{ request('rhesus') == 'Positive' ? 'selected' : '' }}>Positive (+)</option>
                        <option value="Negative" {{ request('rhesus') == 'Negative' ? 'selected' : '' }}>Negative (-)</option>
                        <option value="NT" {{ request('rhesus') == 'NT' ? 'selected' : '' }}>NT</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-ul me-2"></i>Blood Inventory List</span>
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
                            <th>DIN</th>
                            <th>Blood Type</th>
                            <th>Rhesus</th>
                            <th>Volume</th>
                            <th>Collection Date</th>
                            <th>Expiry Date</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bloodInventories as $inventory)
                        <tr>
                            <td><strong>{{ $inventory->din }}</strong></td>
                            <td>
                                <span class="blood-type blood-type-{{ strtolower($inventory->blood_type) }}">
                                    {{ $inventory->blood_type }}
                                </span>
                            </td>
                            <td>
                                @if($inventory->rhesus == 'Positive')
                                <span class="text-success">+</span>
                                @elseif($inventory->rhesus == 'Negative')
                                <span class="text-danger">-</span>
                                @else
                                <span class="text-muted">NT</span>
                                @endif
                            </td>
                            <td>{{ $inventory->volume }} pints</td>
                            <td>{{ \Carbon\Carbon::parse($inventory->date_collected)->format('M d, Y') }}</td>
                            <td>{{ $inventory->expiry_date ? \Carbon\Carbon::parse($inventory->expiry_date)->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $inventory->location }}</td>
                            <td>
                                @switch($inventory->status)
                                @case('tested')
                                <span class="badge-status badge-tested">Tested</span>
                                @break
                                @case('not_tested')
                                <span class="badge-status badge-not-tested">Not Tested</span>
                                @break
                                @case('available')
                                <span class="badge-status badge-available">Available</span>
                                @break
                                @case('used')
                                <span class="badge-status badge-fulfilled">Used</span>
                                @break
                                @case('expired')
                                <span class="badge-status badge-expired">Expired</span>
                                @break
                                @default
                                <span class="badge-status badge-pending">{{ $inventory->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('inventories.show', $inventory->id) }}"><i class="bi bi-eye me-2"></i>View</a></li>
                                        <li><a class="dropdown-item" href="{{ route('inventories.edit', $inventory->id) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('inventories.destroy', $inventory->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this blood bag?')">
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
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mt-2">No blood inventory found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($bloodInventories->hasPages())
        <div class="card-footer">
            {{ $bloodInventories->links() }}
        </div>
        @endif
    </div>
</div>
@endsection