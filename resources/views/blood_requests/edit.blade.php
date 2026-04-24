@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Blood Request</h1>
            <p class="page-subtitle">Update blood transfusion request</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-pencil-square me-2"></i>Request Details</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('requests.update', $bloodRequest->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="blood_type" class="form-label">Blood Type Required</label>
                                <select class="form-select @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type">
                                    <option value="">Select blood type</option>
                                    @foreach($bloodTypes as $type)
                                    <option value="{{ $type }}" {{ $bloodRequest->blood_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('blood_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity (units)</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" value="{{ $bloodRequest->quantity }}" min="1">
                                @error('quantity')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="hospital" class="form-label">Hospital/Medical Facility</label>
                            <input type="text" class="form-control @error('hospital') is-invalid @enderror" 
                                   id="hospital" name="hospital" value="{{ $bloodRequest->hospital }}">
                            @error('hospital')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Contact Phone</label>
                            <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                   id="contact_phone" name="contact_phone" value="{{ $bloodRequest->contact_phone }}">
                            @error('contact_phone')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="reason" class="form-label">Reason for Request</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" name="reason" rows="3">{{ $bloodRequest->reason }}</textarea>
                            @error('reason')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="pending" {{ $bloodRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $bloodRequest->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="fulfilled" {{ $bloodRequest->status == 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                                <option value="rejected" {{ $bloodRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('status')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('requests.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Update Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-clock-history me-2"></i>Request History</span>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Request ID:</strong> #REQ-{{ str_pad($bloodRequest->id, 3, '0', STR_PAD_LEFT) }}</p>
                    <p class="mb-2"><strong>Created:</strong> {{ \Carbon\Carbon::parse($bloodRequest->created_at)->format('M d, Y H:i') }}</p>
                    <p class="mb-0"><strong>Last Updated:</strong> {{ \Carbon\Carbon::parse($bloodRequest->updated_at)->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection