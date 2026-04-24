@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Blood Inventory Details</h1>
            <p class="page-subtitle">View blood bag information</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('inventories.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
            <a href="{{ route('inventories.edit', $bloodInventory->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Info Card -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-droplet me-2"></i>Blood Bag Information</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Donation ID (DIN)</label>
                            <p class="mb-0 fw-bold">{{ $bloodInventory->din }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Bag Type</label>
                            <p class="mb-0 fw-bold">{{ $bloodInventory->type }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Blood Type</label>
                            <p class="mb-0">
                                <span class="blood-type blood-type-{{ strtolower($bloodInventory->blood_type) }}">
                                    {{ $bloodInventory->blood_type }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Rhesus Factor</label>
                            <p class="mb-0 fw-bold">
                                @if($bloodInventory->rhesus == 'Positive')
                                <span class="text-success">Positive (+)</span>
                                @elseif($bloodInventory->rhesus == 'Negative')
                                <span class="text-danger">Negative (-)</span>
                                @else
                                <span class="text-muted">Not Tested</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Volume</label>
                            <p class="mb-0 fw-bold">{{ $bloodInventory->volume }} pints</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                @switch($bloodInventory->status)
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
                                <span class="badge-status badge-pending">{{ $bloodInventory->status }}</span>
                                @endswitch
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collection Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-geo-alt me-2"></i>Collection Information</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Collection Date</label>
                            <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($bloodInventory->date_collected)->format('F d, Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Expiry Date</label>
                            <p class="mb-0 fw-bold">
                                @if($bloodInventory->expiry_date)
                                {{ \Carbon\Carbon::parse($bloodInventory->expiry_date)->format('F d, Y') }}
                                @else
                                <span class="text-muted">Not set</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Location</label>
                            <p class="mb-0 fw-bold">{{ $bloodInventory->location }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Collection Agency</label>
                            <p class="mb-0 fw-bold">{{ $bloodInventory->collection_agency }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Results -->
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-shield-check me-2"></i>Test Results</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">HIV</label>
                            <p class="mb-0">
                                @if($bloodInventory->HIV == 'Negative')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Negative</span>
                                @elseif($bloodInventory->HIV == 'Positive')
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Positive</span>
                                @else
                                <span class="badge bg-secondary">Not Tested</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">HCV</label>
                            <p class="mb-0">
                                @if($bloodInventory->HCV == 'Negative')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Negative</span>
                                @elseif($bloodInventory->HCV == 'Positive')
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Positive</span>
                                @else
                                <span class="badge bg-secondary">Not Tested</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">HBV</label>
                            <p class="mb-0">
                                @if($bloodInventory->HBV == 'Negative')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Negative</span>
                                @elseif($bloodInventory->HBV == 'Positive')
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Positive</span>
                                @else
                                <span class="badge bg-secondary">Not Tested</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Syphilis</label>
                            <p class="mb-0">
                                @if($bloodInventory->Syphilis == 'Negative')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Negative</span>
                                @elseif($bloodInventory->Syphilis == 'Positive')
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Positive</span>
                                @else
                                <span class="badge bg-secondary">Not Tested</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Malaria</label>
                            <p class="mb-0">
                                @if($bloodInventory->Malaria == 'Negative')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Negative</span>
                                @elseif($bloodInventory->Malaria == 'Positive')
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Positive</span>
                                @else
                                <span class="badge bg-secondary">Not Tested</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <span><i class="bi bi-clock-history me-2"></i>Timeline</span>
                </div>
                <div class="card-body">
                    @foreach($bloodInventory->timelines as $timeline)
                    <div class="d-flex justify-content-between mb-0">
                        <small class="text-muted">{{ $timeline->created_at->diffForHumans()}}: {{$timeline->description}}</small>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <span><i class="bi bi-qr-code me-2"></i>QR Code</span>
                </div>
                <div class="card-body text-center">
                    <div class="bg-light p-3 rounded d-inline-block" id="printableArea">
                        <img src="{{ $qrCodeUrl }}" alt="QR Code to upload photos" style="max-width: 100%; height: auto;">
                    </div>
                    <p class="small text-muted mt-2 mb-0">Scan to view details</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-gear me-2"></i>Actions</span>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('inventories.edit', $bloodInventory->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Details
                        </a>
                        <button class="btn btn-outline-secondary" onclick="printDiv()">
                            <i class="bi bi-printer me-2"></i>Print Label
                        </button>
                        <form action="{{ route('inventories.destroy', $bloodInventory->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this blood bag?')">
                                <i class="bi bi-trash me-2"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function printDiv() {
        var printContents = document.getElementById('printableArea').outerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
@endsection