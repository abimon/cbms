@extends('layouts.app')

@section('content')
<div class="fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit User</h1>
            <p class="page-subtitle">Update user information</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-pencil-square me-2"></i>User Details</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('system-users.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="avatar" class="form-label">Profile Photo</label>
                            <div class="input-group d-flex justify-content-center">
                                <div class="m-3 card p-3 border-dark bg-transparent " style="border-style:dashed;width:50%;">
                                    <div class="text-center">
                                        <img id="out" src="{{asset('storage/'.($user->avatar))}}" style="width: 50%; object-fit:contain;" />
                                    </div>
                                    <input type="file" accept="image/jpeg, image/png, image/webp" name="avatar" id="cover" style="display: none;" class="form-control" onchange="loadCoverFile(event)">
                                    <div class="pt-2" id="desc">
                                        @if(!$user->avatar)
                                        <div class="text-center" style="font-size: xxx-large; font-weight:bolder;">
                                            <i class="bi bi-upload"></i>
                                        </div>
                                        @endif
                                        <div class="text-center">
                                            <label for="cover" class="btn btn-success text-white" title="Upload new profile image">Browse</label>
                                        </div>
                                        <div class="text-center prim">*File supported .png .jpg .webp</div>
                                    </div>
                                    <script>
                                        var loadCoverFile = function(event) {
                                            var image = document.getElementById('out');
                                            image.src = URL.createObjectURL(event.target.files[0]);
                                            document.getElementById('cover').value == image.src;

                                        };
                                    </script>
                                </div>
                            </div>
                            @error('avatar')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role *</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="">Select role</option>
                                    <option value="SuperAdmin" {{ $user->role == 'SuperAdmin' ? 'selected' : '' }}>SuperAdmin</option>
                                    <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="Sub-Admin" {{ $user->role == 'Sub-Admin' ? 'selected' : '' }}>Sub-Admin</option>
                                    <option value="Doctor" {{ $user->role == 'Doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="Donor" {{ $user->role == 'Donor' ? 'selected' : '' }}>Donor</option>
                                    <option value="Guest" {{ $user->role == 'Guest' ? 'selected' : '' }}>Guest</option>
                                </select>
                                @error('role')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified"
                                    value="1" {{ $user->is_verified ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_verified">
                                    Email Verified
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('system-users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-info-circle me-2"></i>User Info</span>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>User ID:</strong> #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                    <p class="mb-2"><strong>Created:</strong> {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</p>
                    <p class="mb-0"><strong>Last Updated:</strong> {{ \Carbon\Carbon::parse($user->updated_at)->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <span><i class="bi bi-shield me-2"></i>Role Permissions</span>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small">
                        <li class="mb-2"><span class="badge bg-danger me-1">SuperAdmin</span> Full system access</li>
                        <li class="mb-2"><span class="badge bg-primary me-1">Admin</span> Manage all data</li>
                        <li class="mb-2"><span class="badge bg-info me-1">Sub-Admin</span> Limited management</li>
                        <li class="mb-2"><span class="badge bg-success me-1">Doctor</span> Request blood</li>
                        <li class="mb-2"><span class="badge bg-warning me-1">Donor</span> Donate blood</li>
                        <li><span class="badge bg-secondary me-1">Guest</span> View only</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection