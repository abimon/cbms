@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Add to Storage</h1>
            <p class="page-subtitle">Store blood bag in inventory</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-box-seam me-2"></i>Storage Details</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('storages.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="bloodbag_id" class="form-label">Blood Bag *</label>
                            <select class="form-select @error('bloodbag_id') is-invalid @enderror" id="bloodbag_id" name="bloodbag_id" required>
                                <option value="">Select blood bag</option>
                                @php
                                    $inventories = \App\Models\BloodInventory::all();
                                @endphp
                                @foreach($inventories as $inv)
                                <option value="{{ $inv->id }}">{{ $inv->din }} - {{ $inv->blood_type }} ({{ $inv->volume }}ml)</option>
                                @endforeach
                            </select>
                            @error('bloodbag_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bank_id" class="form-label">Blood Bank *</label>
                            <select class="form-select @error('bank_id') is-invalid @enderror" id="bank_id" name="bank_id" required>
                                <option value="">Select blood bank</option>
                                @php
                                    $banks = \App\Models\BloodBank::all();
                                @endphp
                                @foreach($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->name }} - {{ $bank->location }}</option>
                                @endforeach
                            </select>
                            @error('bank_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="transfered" {{ old('status') == 'transfered' ? 'selected' : '' }}>Transferred</option>
                                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                            @error('status')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('storages.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Add to Storage
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-info-circle me-2"></i>Storage Guidelines</span>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="bi bi-thermometer-half text-primary me-2"></i>
                            Store at 2-6°C for whole blood
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-clock text-primary me-2"></i>
                            Maximum storage: 35 days
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-shield-check text-primary me-2"></i>
                            Only tested blood can be stored
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-geo-alt text-primary me-2"></i>
                            Track location by blood bank
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection