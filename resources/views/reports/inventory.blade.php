@extends('layouts.reports')
@section('content')
<div class="">
    <div class="text-center">
        Inventory Report As at {{ date('jS F Y H:i:s') }}
    </div>
    <div class="card">
        <div class="card-header">Inventory Totals by Blood Group</div>
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
        <div class="card-header">Blood Bags</div>
        <div class="card-body p-0">
            <div class="table-responsive" style="white-space: nowrap;min-height: 50vh;">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>DIN</th>
                            <th>Blood Type</th>
                            <th>Volume</th>
                            <th>Collection Date</th>
                            <th>Expiry Date</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($selectedBank->inventories->sortBy('status') as $inventory)
                        <tr>
                            <td>{{ $loop->iteration}}</td>
                            <td><strong>{{ $inventory->din }}</strong></td>
                            <td>
                                {{ $inventory->blood_type }}
                                @if($inventory->rhesus == 'Positive')
                                <span class="text-success">+</span>
                                @elseif($inventory->rhesus == 'Negative')
                                <span class="text-danger">-</span>
                                @else
                                <span class="text-muted">NT</span>
                                @endif
                            </td>
                            <td>{{ $inventory->volume }} pints</td>
                            <td>{{ \Carbon\Carbon::parse($inventory->date_collected)->format('M d, Y') }}</td>
                            <td>{{ $inventory->expiry_date ? \Carbon\Carbon::parse($inventory->expiry_date)->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $inventory->location }}</td>
                            <td>
                                @switch($inventory->status)
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
                                <span class="badge-status badge-pending">{{ $inventory->status }}</span>
                                @endswitch
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mt-2">No blood inventory found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">Withdrawal Report</div>
        <div class="card-body p-0">
            <div class="table-responsive" style="white-space: nowrap;min-height: 50vh;">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>DIN</th>
                            <th>Blood Type</th>
                            <th>Volume</th>
                            <th>Collection Date</th>
                            <th>Expiry Date</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($selectedBank->inventories->sortBy('status') as $inventory)
                        <tr>
                            <td>{{ $loop->iteration}}</td>
                            <td><strong>{{ $inventory->din }}</strong></td>
                            <td>
                                {{ $inventory->blood_type }}
                                @if($inventory->rhesus == 'Positive')
                                <span class="text-success">+</span>
                                @elseif($inventory->rhesus == 'Negative')
                                <span class="text-danger">-</span>
                                @else
                                <span class="text-muted">NT</span>
                                @endif
                            </td>
                            <td>{{ $inventory->volume }} pints</td>
                            <td>{{ \Carbon\Carbon::parse($inventory->date_collected)->format('M d, Y') }}</td>
                            <td>{{ $inventory->expiry_date ? \Carbon\Carbon::parse($inventory->expiry_date)->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $inventory->location }}</td>
                            <td>
                                @switch($inventory->status)
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
                                <span class="badge-status badge-pending">{{ $inventory->status }}</span>
                                @endswitch
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mt-2">No blood inventory found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
@endsection