@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Blood Banks</h1>
            <p class="page-subtitle">Manage blood bank locations</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBankModal">
            <i class="bi bi-plus-lg me-2"></i>Add Blood Bank
        </button>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card primary">
                <i class="bi bi-hospital stat-icon"></i>
                <div class="stat-value">{{ $banks->count() }}</div>
                <div class="stat-label">Total Blood Banks</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card success">
                <i class="bi bi-geo-alt stat-icon"></i>
                <div class="stat-value">{{ $banks->unique('location')->count() }}</div>
                <div class="stat-label">Locations</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card info">
                <i class="bi bi-telephone stat-icon"></i>
                <div class="stat-value">{{ $banks->whereNotNull('contact_phone')->count() }}</div>
                <div class="stat-label">With Contact Info</div>
            </div>
        </div>
    </div>

    <!-- Blood Banks Grid -->
    <div class="row">
        @forelse($banks as $bank)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">{{ $bank->name }}</span>
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
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-droplet-fill text-danger fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Blood Bank</p>
                            <strong>{{ $bank->name }}</strong>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <i class="bi bi-geo-alt text-muted me-2"></i>
                        <span class="text-muted">Location:</span>
                        <span>{{ $bank->location }}</span>
                    </div>
                    @if($bank->contact_phone)
                    <div class="mb-2">
                        <i class="bi bi-telephone text-muted me-2"></i>
                        <span class="text-muted">Phone:</span>
                        <span>{{ $bank->contact_phone }}</span>
                    </div>
                    @endif
                    @if($bank->email)
                    <div class="mb-0">
                        <i class="bi bi-envelope text-muted me-2"></i>
                        <span class="text-muted">Email:</span>
                        <span>{{ $bank->email }}</span>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-transparent">
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-droplet me-1"></i>View Inventory
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-hospital fs-1"></i>
                    <p class="mt-2">No blood banks found</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBankModal">
                        <i class="bi bi-plus-lg me-2"></i>Add First Blood Bank
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Bank Modal -->
<div class="modal fade" id="addBankModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-hospital me-2"></i>Add New Blood Bank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('banks.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Bank Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location *</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_person" class="form-label">Contact Person</label>
                        <input type="text" class="form-control" id="contact_person" name="contact_person">
                    </div>
                    <div class="mb-3">
                        <label for="contact_phone" class="form-label">Contact Phone</label>
                        <input type="text" class="form-control" id="contact_phone" name="contact_phone">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Blood Bank</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection