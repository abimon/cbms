@extends('layouts.app')
@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Welcome back, {{ auth()->user()->name }}!</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary">
                <i class="bi bi-download me-2"></i>Export
            </button>
            <button class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>New Request
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card primary">
                <i class="bi bi-droplet stat-icon"></i>
                <div class="stat-value">{{ $totalInventory ?? 0 }}</div>
                <div class="stat-label">Total Blood Units</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card success">
                <i class="bi bi-check-circle stat-icon"></i>
                <div class="stat-value">{{ $pendingRequests ?? 0 }}</div>
                <div class="stat-label">Pending Requests</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card warning">
                <i class="bi bi-people stat-icon"></i>
                <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card info">
                <i class="bi bi-hospital stat-icon"></i>
                <div class="stat-value">{{ $banks->count() ?? 0 }}</div>
                <div class="stat-label">Blood Banks</div>
            </div>
        </div>
    </div>

    <!-- Blood Inventory chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-bar-chart me-2"></i>Blood Inventory by Type</span>
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div>
                        <canvas id="myChart" style="width:100%;"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-clock-history me-2"></i>Recent Blood Requests</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Blood Type</th>
                                    <th>Quantity</th>
                                    <th>Hospital</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $request)
                                <tr>
                                    <td>#REQ-{{$request->id}}</td>
                                    <td><span class="blood-type blood-type-a">A+</span></td>
                                    <td>{{$request->quantity}} units</td>
                                    <td>{{$request->hospital}}</td>
                                    <td><span class="badge-status badge-pending">{{$request->status}}</span></td>
                                    <td>{{$request->created_at->diffForHuman()}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-lightning me-2"></i>Quick Actions</span>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('inventories.create') }}" class="btn btn-outline-primary text-start">
                            <i class="bi bi-droplet me-2"></i>Add Blood Inventory
                        </a>
                        <a href="{{ route('requests.create') }}" class="btn btn-outline-danger text-start">
                            <i class="bi bi-clipboard-plus me-2"></i>New Blood Request
                        </a>
                        <a href="{{ route('banks.index') }}" class="btn btn-outline-success text-start">
                            <i class="bi bi-hospital me-2"></i>Manage Blood Banks
                        </a>
                        <a href="{{ route('withdrawals.index') }}" class="btn btn-outline-warning text-start">
                            <i class="bi bi-arrow-down-circle me-2"></i>View Withdrawals
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('myChart');
    console.log(<?php echo json_encode($chartData); ?>);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($typeLabels); ?>,
            datasets: <?php echo json_encode($chartData); ?>
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection