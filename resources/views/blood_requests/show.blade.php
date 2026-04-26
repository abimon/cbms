@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="page-title">Request details</h2>
            <p class="text-muted mb-0">View the hospital blood request and change status.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('blood-requests.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
            <a href="{{ route('blood-requests.edit', $bloodRequest) }}" class="btn btn-primary btn-sm">Edit request</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="app-card p-4">
                <div class="mb-4">
                    <span class="badge bg-warning bg-opacity-10 text-warning">{{ ucfirst($bloodRequest->status) }}</span>
                </div>
                <dl class="row align-items-center">
                    <dt class="col-sm-5 text-muted">Hospital</dt>
                    <dd class="col-sm-7">{{ $bloodRequest->hospital }}</dd>
                    <dt class="col-sm-5 text-muted">Blood type</dt>
                    <dd class="col-sm-7">{{ $bloodRequest->blood_type }}</dd>
                    <dt class="col-sm-5 text-muted">Quantity</dt>
                    <dd class="col-sm-7">{{ $bloodRequest->quantity }} unit(s)</dd>
                    <dt class="col-sm-5 text-muted">Contact phone</dt>
                    <dd class="col-sm-7">{{ $bloodRequest->contact_phone }}</dd>
                    <dt class="col-sm-5 text-muted">Created</dt>
                    <dd class="col-sm-7">{{ $bloodRequest->created_at->format('M d, Y') }}</dd>
                </dl>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="app-card p-4">
                <h5 class="mb-3">Request reason</h5>
                <p class="text-muted mb-0">{{ $bloodRequest->reason ?? 'No reason provided.' }}</p>
            </div>
        </div>
    </div>
@endsection
