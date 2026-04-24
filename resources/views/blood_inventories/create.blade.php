@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Add Blood Inventory</h1>
            <p class="page-subtitle">Register new blood donation</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-droplet me-2"></i>Blood Information</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('inventories.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="din" class="form-label">Donation Identification Number (DIN) *</label>
                                <input type="text" class="form-control @error('din') is-invalid @enderror"
                                    id="din" name="din" value="{{ old('din') }}" required
                                    placeholder="e.g., DIN-2026-001">
                                @error('din')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Bag Type *</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Select bag type</option>
                                    <option value="Whole Blood" {{ old('type') == 'Whole Blood' ? 'selected' : '' }}>Whole Blood</option>
                                    <option value="RBC" {{ old('type') == 'RBC' ? 'selected' : '' }}>Red Blood Cells</option>
                                    <option value="Plasma" {{ old('type') == 'Plasma' ? 'selected' : '' }}>Plasma</option>
                                    <option value="Platelets" {{ old('type') == 'Platelets' ? 'selected' : '' }}>Platelets</option>
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
                                    <option value="">Select blood type</option>
                                    <option value="A" {{ old('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ old('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ old('blood_type') == 'O' ? 'selected' : '' }}>O</option>
                                    <option value="NT" {{ old('blood_type') == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                </select>
                                @error('blood_type')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="rhesus" class="form-label">Rhesus Factor *</label>
                                <select class="form-select @error('rhesus') is-invalid @enderror" id="rhesus" name="rhesus" required>
                                    <option value="">Select rhesus</option>
                                    <option value="Positive" {{ old('rhesus') == 'Positive' ? 'selected' : '' }}>Positive (+)</option>
                                    <option value="Negative" {{ old('rhesus') == 'Negative' ? 'selected' : '' }}>Negative (-)</option>
                                    <option value="NT" {{ old('rhesus') == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                </select>
                                @error('rhesus')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="volume" class="form-label">Volume (pints) *</label>
                                <input type="text" class="form-control @error('volume') is-invalid @enderror"
                                    id="volume" name="volume" value="{{ old('volume') }}" required
                                    placeholder="e.g., 450">
                                @error('volume')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_collected" class="form-label">Collection Date *</label>
                                <input type="date" class="form-control @error('date_collected') is-invalid @enderror"
                                    id="date_collected" name="date_collected" value="{{ old('date_collected') }}" required>
                                @error('date_collected')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Collection Location *</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="location" name="location" value="{{ old('location') }}" required
                                    placeholder="e.g., City Center Camp">
                                @error('location')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="collection_agency" class="form-label">Collection Agency *</label>
                            <select class="form-select @error('collection_agency') is-invalid @enderror" id="collection_agency" name="collection_agency" required>
                                <option value="">Select agency</option>
                                @foreach (App\Models\BloodBank::select('name')->get() as $agency)
                                <option value="{{$agency->name}}" {{ old('collection_agency') == $agency->name ? 'selected' : '' }}>{{$agency->name}}</option>
                                @endforeach
                            </select>
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
                                    <option value="NT" {{ old('HIV') == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                    <option value="Negative" {{ old('HIV') == 'Negative' ? 'selected' : '' }}>Negative</option>
                                    <option value="Positive" {{ old('HIV') == 'Positive' ? 'selected' : '' }}>Positive</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="HCV" class="form-label">HCV</label>
                                <select class="form-select" id="HCV" name="HCV" required>
                                    <option value="NT" {{ old('HCV') == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                    <option value="Negative" {{ old('HCV') == 'Negative' ? 'selected' : '' }}>Negative</option>
                                    <option value="Positive" {{ old('HCV') == 'Positive' ? 'selected' : '' }}>Positive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="HBV" class="form-label">HBV</label>
                                <select class="form-select" id="HBV" name="HBV" required>
                                    <option value="NT" {{ old('HBV') == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                    <option value="Negative" {{ old('HBV') == 'Negative' ? 'selected' : '' }}>Negative</option>
                                    <option value="Positive" {{ old('HBV') == 'Positive' ? 'selected' : '' }}>Positive</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Syphilis" class="form-label">Syphilis</label>
                                <select class="form-select" id="Syphilis" name="Syphilis" required>
                                    <option value="NT" {{ old('Syphilis') == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                    <option value="Negative" {{ old('Syphilis') == 'Negative' ? 'selected' : '' }}>Negative</option>
                                    <option value="Positive" {{ old('Syphilis') == 'Positive' ? 'selected' : '' }}>Positive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="Malaria" class="form-label">Malaria</label>
                            <select class="form-select" id="Malaria" name="Malaria" required>
                                <option value="NT" {{ old('Malaria') == 'NT' ? 'selected' : '' }}>Not Tested</option>
                                <option value="Negative" {{ old('Malaria') == 'Negative' ? 'selected' : '' }}>Negative</option>
                                <option value="Positive" {{ old('Malaria') == 'Positive' ? 'selected' : '' }}>Positive</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('inventories.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Save Blood Bag
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-info-circle me-2"></i>Instructions</span>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="bi bi-1-circle text-primary me-2"></i>
                            Enter the unique DIN from the blood bag label
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-2-circle text-primary me-2"></i>
                            Select the correct blood type and rhesus factor
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-3-circle text-primary me-2"></i>
                            Fill in all required test results after laboratory screening
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-4-circle text-primary me-2"></i>
                            Ensure all information is accurate before saving
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection