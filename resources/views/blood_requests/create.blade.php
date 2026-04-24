@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">New Blood Request</h1>
            <p class="page-subtitle">Submit a blood transfusion request</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-clipboard2-pulse me-2"></i>Request Details</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('requests.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="blood_type" class="form-label">Blood Type Required *</label>
                                <select class="form-select @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type" required>
                                    <option value="">Select blood type</option>
                                    @foreach($bloodTypes as $type)
                                    <option value="{{ $type }}" {{ old('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('blood_type')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity (units) *</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                    id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                                @error('quantity')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="hospital" class="form-label">Hospital/Medical Facility *</label>
                            <select class="form-select @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type" required>
                                <option value="">Select Facility</option>
                                @foreach(App\Models\BloodBank::select('name')->get() as $facility)
                                <option value="{{ $facility->name }}" {{ old('blood_type') == $facility->name ? 'selected' : '' }}>{{ $facility->name }}</option>
                                @endforeach
                            </select>
                            @error('hospital')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Contact Phone *</label>
                            <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                                id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}" required
                                placeholder="e.g., +1234567890">
                            @error('contact_phone')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="reason" class="form-label">Reason for Request</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror"
                                id="reason" name="reason" rows="3"
                                placeholder="Describe the medical reason for this blood request">{{ old('reason') }}</textarea>
                            @error('reason')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('requests.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-info-circle me-2"></i>Blood Type Guide</span>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="blood-type blood-type-a">A+</span>
                        <span class="blood-type blood-type-a">A-</span>
                        <span class="blood-type blood-type-b">B+</span>
                        <span class="blood-type blood-type-b">B-</span>
                        <span class="blood-type blood-type-ab">AB+</span>
                        <span class="blood-type blood-type-ab">AB-</span>
                        <span class="blood-type blood-type-o">O+</span>
                        <span class="blood-type blood-type-o">O-</span>
                    </div>
                    <hr>
                    <p class="small text-muted mb-2"><strong>Universal Donor:</strong> O-</p>
                    <p class="small text-muted mb-2"><strong>Universal Recipient:</strong> AB+</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <span><i class="bi bi-telephone me-2"></i>Emergency Contacts</span>
                </div>
                <div class="card-body">
                    <p class="small mb-2"><strong>Blood Bank:</strong> +1-800-BLOOD</p>
                    <p class="small mb-0"><strong>Emergency:</strong> 911</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection