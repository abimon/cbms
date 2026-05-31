@extends('layouts.app')
@section('content')
<div class="fade-in container">
    <h1>All Blood Banks</h1>
    @forelse($banks as $bank)
    <div class="col-12 mb-4">
        <div class="card h-100 mb-3">
            <div class="card-header d-flex justify-content-between">
                <span class="fw-bold">{{ $bank->name }}</span>
                <div class="d-flex justify-content-end">
                    <div class="me-2">
                        {{ $bank->users->count() }} <i class="bi bi-people"></i>
                    </div>
                    <div class="me-2">
                        {{ $bank->withdrawals->count() }} <i class="bi bi-box-arrow-up"></i>
                    </div>
                    <div class="me-2">
                        {{ $bank->requests->count() }} <i class="bi bi-question-circle"></i>
                    </div>
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
                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Delete</a>
                            </li>
                        </ul>
                    </div>
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
                <div class="row">

                    @forelse (json_decode($bank->threshold) as $item)
                    @php
                    $_item = preg_split('/(?=[+-])/', $item->blood_group);
                    $bg = $_item[0];
                    $rf = $_item[1]=='+' ? 'Positive' : 'Negative';
                    $level = floor(($bank->inventories->where('blood_type', $bg)->where('rhesus', $rf)->sum('volume') / $item->threshold)*50);

                    @endphp
                    <div class="col-md-4 col-6 mb-2">
                        <div class="row">
                            <div class="col-2  d-flex align-items-center">
                                <span class="badge bg-secondary">{{ $item->blood_group}}</span>
                            </div>
                            <div class="col-8">
                                <div class="progress {{ $level<50?'bg-danger':($level<75?'bg-warning':'bg-success') }}" role="progressbar" aria-label="Animated striped example" aria-valuenow="{{$level??0}} " aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-success" style="width: {{ $level }}%"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-muted">No thresholds defined for this bank.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="p-3 text-end">
                <form method="POST" action="{{ route('admin.login_as', $bank->id) }}">
                    @csrf
                    <button class="btn btn-sm btn-success">
                        Login to {{ $bank->name }}
                    </button>
                </form>
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
    <div class="text-center">
        {{ $banks->links() }}
    </div>
</div>
@endsection