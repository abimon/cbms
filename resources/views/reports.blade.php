@extends('layouts.app')
@section('content')
<div class="fade-in container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h2 class="mb-1">Reports</h2>

        </div>

    </div>

    <div class="card p-4 mb-4" id="printable-area">

        <div class="d-flex justified-content-between">
            <div class="">
                <h3>{{ $selectedBank->name }} Bank Reports</h3>
                <p class="text-muted mb-0">Overview and per-bank reports with printable and exportable charts and tables.</p>
            </div>
            <div class="">
                <a href="/emailreport?action=mail" style="text-decoration: none;"><button type="button" class="btn btn-sm btn-outline-success">Email Report Summary</button></a>
                <a href="/emailreport?action=download" style="text-decoration: none;"><button type="button" class="btn btn-sm btn-outline-primary">Download PDF</button></a>
            </div>
        </div>

        <ul class="nav nav-pills mb-4" id="report-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab">Overview</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="inventory-tab" data-bs-toggle="pill" data-bs-target="#inventory" type="button" role="tab">Inventory</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="requests-tab" data-bs-toggle="pill" data-bs-target="#requests" type="button" role="tab">Requests</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="withdrawals-tab" data-bs-toggle="pill" data-bs-target="#withdrawals" type="button" role="tab">Withdrawals</button>
            </li>
        </ul>

        <div class="tab-content" id="report-tabs-content">
            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <div class="row gy-4">
                    <div class="col-lg-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Total Inventory</h5>
                                <p class="display-6 mb-0">{{ number_format(array_sum($inventoryByGroup)) }}</p>
                                <p class="text-muted">Total blood units available in the selected scope.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Pending Requests</h5>
                                <p class="display-6 mb-0">{{ $requestStats['pending'] ?? 0 }}</p>
                                <p class="text-muted">Requests waiting to be fulfilled.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Open Withdrawals</h5>
                                <p class="display-6 mb-0">{{ $withdrawalStats['pending'] ?? 0 }}</p>
                                <p class="text-muted">Withdrawals still in progress or uncompleted.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gy-4 mt-4">
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">Inventory by blood group</div>
                            <div class="card-body">
                                <canvas id="inventoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8  row h-100">
                        <div class="col-md-6  mb-4 h-100">
                            <div class="card">
                                <div class="card-header">Request status</div>
                                <div class="card-body">
                                    <canvas id="requestChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4 h-100">
                            <div class="card">
                                <div class="card-header">Withdrawal status</div>
                                <div class="card-body">
                                    <canvas id="withdrawalChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                <div class="card">
                    <div class="card-header">Inventory totals by blood group</div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped" id="inventory-table">
                            <thead>
                                <tr>
                                    <th>Blood Group</th>
                                    <th>Units</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventoryByGroup as $group => $total)
                                <tr>
                                    <td>{{ $group }}</td>
                                    <td>{{ $total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">{{ $selectedBank->name }} inventory breakdown</div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped" id="inventory-bank-table">
                            <thead>
                                <tr>
                                    <th>Blood Group</th>
                                    <th>Units</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedBankInventory as $group => $total)
                                <tr>
                                    <td>{{ $group }}</td>
                                    <td>{{ $total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="requests" role="tabpanel" aria-labelledby="requests-tab">
                <div class="row gy-4">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">Request status summary</div>
                            <div class="card-body table-responsive">
                                <table class="table table-striped" id="requests-table">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requestStats as $status => $count)
                                        <tr>
                                            <td>{{ ucfirst($status) }}</td>
                                            <td>{{ $count }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if(isset($selectedBank))
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">{{ $selectedBank->name }} request status</div>
                            <div class="card-body table-responsive">
                                <table class="table table-striped" id="requests-bank-table">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selectedBankRequests as $status => $count)
                                        <tr>
                                            <td>{{ ucfirst($status) }}</td>
                                            <td>{{ $count }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="tab-pane fade" id="withdrawals" role="tabpanel" aria-labelledby="withdrawals-tab">
                <div class="row gy-4">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">Withdrawal status summary</div>
                            <div class="card-body table-responsive">
                                <table class="table table-striped" id="withdrawals-table">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($withdrawalStats as $status => $count)
                                        <tr>
                                            <td>{{ ucfirst($status) }}</td>
                                            <td>{{ $count }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if(isset($selectedBank))
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">{{ $selectedBank->name }} withdrawal status</div>
                            <div class="card-body table-responsive">
                                <table class="table table-striped" id="withdrawals-bank-table">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selectedBankWithdrawals as $status => $count)
                                        <tr>
                                            <td>{{ ucfirst($status) }}</td>
                                            <td>{{ $count }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #printable-area,
        #printable-area * {
            visibility: visible;
        }

        #printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .nav-pills,
        form,
        .btn {
            display: none !important;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const bloodGroups = @json($bloodGroups);
    const inventoryValues = @json(array_values($inventoryByGroup));
    const requestStatuses = @json(array_keys($requestStats));
    const requestCounts = @json(array_values($requestStats));
    const withdrawalStatuses = @json(array_keys($withdrawalStats));
    const withdrawalCounts = @json(array_values($withdrawalStats));

    window.addEventListener('DOMContentLoaded', function() {
        const inventoryCtx = document.getElementById('inventoryChart');
        if (inventoryCtx) new Chart(inventoryCtx, {
            type: 'bar',
            data: {
                labels: bloodGroups,
                datasets: [{
                    label: 'Units',
                    data: inventoryValues,
                    backgroundColor: 'rgba(13, 110, 253, 0.65)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const requestCtx = document.getElementById('requestChart');
        if (requestCtx) new Chart(requestCtx, {
            type: 'doughnut',
            data: {
                labels: requestStatuses,
                datasets: [{
                    data: requestCounts,
                    backgroundColor: ['#0d6efd', '#ffc107', '#198754', '#dc3545', '#6c757d']
                }]
            },
            options: {
                responsive: true
            }
        });

        const withdrawalCtx = document.getElementById('withdrawalChart');
        if (withdrawalCtx) new Chart(withdrawalCtx, {
            type: 'doughnut',
            data: {
                labels: withdrawalStatuses,
                datasets: [{
                    data: withdrawalCounts,
                    backgroundColor: ['#0d6efd', '#ffc107', '#198754', '#dc3545', '#6c757d']
                }]
            },
            options: {
                responsive: true
            }
        });
    });
</script>
@endsection