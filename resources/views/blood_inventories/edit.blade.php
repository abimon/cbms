@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Blood Inventory</h1>
            <p class="page-subtitle">Update blood bag information</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-droplet me-2"></i>Blood Bag Information</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('inventories.update', $bloodInventory->id) }}">
                        @csrf
                        @method('PUT')
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="din" class="form-label">Donation ID (DIN) *</label>
                                <input type="text" class="form-control @error('din') is-invalid @enderror" 
                                       id="din" name="din" value="{{ old('din', $bloodInventory->din) }}" required>
                                @error('din')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Bag Type *</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="Whole Blood" {{ $bloodInventory->type == 'Whole Blood' ? 'selected' : '' }}>Whole Blood</option>
                                    <option value="RBC" {{ $bloodInventory->type == 'RBC' ? 'selected' : '' }}>Red Blood Cells</option>
                                    <option value="Plasma" {{ $bloodInventory->type == 'Plasma' ? 'selected' : '' }}>Plasma</option>
                                    <option value="Platelets" {{ $bloodInventory->type == 'Platelets' ? 'selected' : '' }}>Platelets</option>
                                </select>
                                @error('type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="blood_type" class="form-label">Blood Type *</label>
                                <select class="form-select @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type" required>
                                    <option value="A" {{ $bloodInventory->blood_type == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ $bloodInventory->blood_type == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ $bloodInventory->blood_type == 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ $bloodInventory->blood_type == 'O' ? 'selected' : '' }}>O</option>
                                    <option value="NT" {{ $bloodInventory->blood_type == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                </select>
                                @error('blood_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="rhesus" class="form-label">Rhesus Factor *</label>
                                <select class="form-select @error('rhesus') is-invalid @enderror" id="rhesus" name="rhesus" required>
                                    <option value="Positive" {{ $bloodInventory->rhesus == 'Positive' ? 'selected' : '' }}>Positive (+)</option>
                                    <option value="Negative" {{ $bloodInventory->rhesus == 'Negative' ? 'selected' : '' }}>Negative (-)</option>
                                    <option value="NT" {{ $bloodInventory->rhesus == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                </select>
                                @error('rhesus')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="volume" class="form-label">Volume (pints) *</label>
                                <input type="text" class="form-control @error('volume') is-invalid @enderror" 
                                       id="volume" name="volume" value="{{ old('volume', $bloodInventory->volume) }}" required>
                                @error('volume')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_collected" class="form-label">Collection Date *</label>
                                <input type="date" class="form-control @error('date_collected') is-invalid @enderror" 
                                       id="date_collected" name="date_collected" value="{{ old('date_collected', $bloodInventory->date_collected) }}" required>
                                @error('date_collected')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="release_date" class="form-label">Released Date</label>
                                <input type="date" class="form-control @error('release_date') is-invalid @enderror" 
                                       id="release_date" name="release_date" value="{{ old('release_date', $bloodInventory->release_date) }}">
                                @error('release_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Collection Location *</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location', $bloodInventory->location) }}" required>
                            @error('location')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="collection_agency" class="form-label">Collection Agency *</label>
                            <input type="text" class="form-control @error('collection_agency') is-invalid @enderror" 
                                   id="collection_agency" name="collection_agency" value="{{ old('collection_agency', $bloodInventory->collection_agency) }}" required>
                            @error('collection_agency')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3"><i class="bi bi-shield-check me-2"></i>Test Results</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="HIV" class="form-label">HIV</label>
                                <select class="form-select" id="HIV" name="HIV" required>
                                    <option value="NT" {{ $bloodInventory->HIV == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                    <option value="Negative" {{ $bloodInventory->HIV == 'Negative' ? 'selected' : '' }}>Negative</option>
                                    <option value="Positive" {{ $bloodInventory->HIV == 'Positive' ? 'selected' : '' }}>Positive</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="HCV" class="form-label">HCV</label>
                                <select class="form-select" id="HCV" name="HCV" required>
                                    <option value="NT" {{ $bloodInventory->HCV == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                    <option value="Negative" {{ $bloodInventory->HCV == 'Negative' ? 'selected' : '' }}>Negative</option>
                                    <option value="Positive" {{ $bloodInventory->HCV == 'Positive' ? 'selected' : '' }}>Positive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="HBV" class="form-label">HBV</label>
                                <select class="form-select" id="HBV" name="HBV" required>
                                    <option value="NT" {{ $bloodInventory->HBV == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                    <option value="Negative" {{ $bloodInventory->HBV == 'Negative' ? 'selected' : '' }}>Negative</option>
                                    <option value="Positive" {{ $bloodInventory->HBV == 'Positive' ? 'selected' : '' }}>Positive</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Syphilis" class="form-label">Syphilis</label>
                                <select class="form-select" id="Syphilis" name="Syphilis" required>
                                    <option value="NT" {{ $bloodInventory->Syphilis == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                    <option value="Negative" {{ $bloodInventory->Syphilis == 'Negative' ? 'selected' : '' }}>Negative</option>
                                    <option value="Positive" {{ $bloodInventory->Syphilis == 'Positive' ? 'selected' : '' }}>Positive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="Malaria" class="form-label">Malaria</label>
                            <select class="form-select" id="Malaria" name="Malaria" required>
                                <option value="NT" {{ $bloodInventory->Malaria == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                <option value="Negative" {{ $bloodInventory->Malaria == 'Negative' ? 'selected' : '' }}>Negative</option>
                                <option value="Positive" {{ $bloodInventory->Malaria == 'Positive' ? 'selected' : '' }}>Positive</option>
                            </select>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Status</h5>

                        <div class="mb-4">
                            <label for="status" class="form-label">Current Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="not_tested" {{ $bloodInventory->status == 'not_tested' ? 'selected' : '' }}>Not Tested</option>
                                <option value="tested" {{ $bloodInventory->status == 'tested' ? 'selected' : '' }}>Tested</option>
                                <option value="available" {{ $bloodInventory->status == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="used" {{ $bloodInventory->status == 'used' ? 'selected' : '' }}>Used</option>
                                <option value="expired" {{ $bloodInventory->status == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                            @error('status')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('inventories.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Update Blood Bag
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <span><i class="bi bi-clock-history me-2"></i>Record Info</span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">ID</span>
                        <strong>#{{ $bloodInventory->id }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Created</span>
                        <strong>{{ \Carbon\Carbon::parse($bloodInventory->created_at)->format('M d, Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-0">
                        <span class="text-muted">Updated</span>
                        <strong>{{ \Carbon\Carbon::parse($bloodInventory->updated_at)->format('M d, Y') }}</strong>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-info-circle me-2"></i>Instructions</span>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="bi bi-1-circle text-primary me-2"></i>
                            DIN must be unique
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-2-circle text-primary me-2"></i>
                            Update test results after lab screening
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-3-circle text-primary me-2"></i>
                            Change status when blood is used or expires
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-4-circle text-primary me-2"></i>
                            Expiry date is auto-calculated (35 days)
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection