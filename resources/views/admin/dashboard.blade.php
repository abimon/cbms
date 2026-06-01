@extends('layouts.app')
@section('content')
<div class="fade-in container">
    <div class="d-flex justify-content-between mb-2">
        <h2 class="mb-0">Admin Dashboard</h2>
        <form method="GET" action="{{ route('admin.dashboard') }}" class="position-relative w-50">
            <div class="input-group ">
                <input id="bank-search" name="search" type="search" class="form-control" placeholder="Search banks by name..." value="{{ request('search') }}" autocomplete="off" aria-label="Search banks">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
            <div id="bank-suggestion-list" class="list-group position-absolute w-100 mt-1" style="z-index: 1050; display: none;"></div>
        </form>
    </div>
    @forelse($banks as $bank)
    <div class="col-12 mb-4">
        <div class="card h-100 mb-3">
            <div class="card-header d-flex justify-content-between">
                <span class="fw-bold">{{ $bank['name'] }}</span>
                <div class="d-flex justify-content-end">
                    <div class="me-2">
                        {{ $bank['users'] }} <i class="bi bi-people"></i>
                    </div>
                    <div class="me-2">
                        {{ $bank['withdrawals'] }} <i class="bi bi-box-arrow-up"></i>
                    </div>
                    <div class="me-2">
                        {{ $bank['requests'] }} <i class="bi bi-question-circle"></i>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2"></i>View</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Delete</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-danger bg-opacity-10 p-3 rounded me-3">
                        <i class="bi bi-droplet-fill text-danger fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Blood Bank</p>
                        <strong>{{ $bank['name'] }}</strong>
                    </div>
                </div>
                <hr>
                <div class="row">

                    @forelse ($bank['inventory'] as $item)
                    <?php
                    $level = floor(($item['quantity'] / $item['threshold']) * 100);
                    ?>
                    <div class="col-md-4 col-6 mb-2">
                        <div class="row">
                            <div class="col-2  d-flex align-items-center">
                                <span class="badge bg-secondary">{{ $item['blood_group']}}</span>
                            </div>
                            <div class="col-8">
                                <div class="progress {{ $level<50?'bg-danger':($level<75?'bg-warning':'bg-success') }}" role="progressbar" aria-label="Animated striped example" aria-valuenow="{{$level}} " aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-success" style="width: <?php $level ?>%">{{ $level?$level.'%':''}}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-muted">No thresholds defined for this bank.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="p-3 text-end">
                <form method="POST" action="{{ route('admin.login_as', $bank['id']) }}">
                    @csrf
                    <button class="btn btn-sm btn-success">
                        Login to {{ $bank['name'] }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-hospital fs-1"></i>
                <p class="mt-2">No blood banks found</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBankModal">
                    <i class="bi bi-plus-lg me-2"></i>Add First Blood Bank
                </button>
            </div>
        </div>
    </div>
    @endforelse
    <div class="text-center">
        {{ $_banks->appends(request()->query())->links() }}
    </div>
</div>

<script>
    (function() {
        const searchInput = document.getElementById('bank-search');
        const suggestionList = document.getElementById('bank-suggestion-list');
        const searchUrl = '/admin/banks/search';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let timer = null;

        function hideSuggestions() {
            suggestionList.style.display = 'none';
            suggestionList.innerHTML = '';
        }

        let selectedIndex = -1;

        function getSuggestionItems() {
            return Array.from(suggestionList.querySelectorAll('button[data-id]'));
        }

        function setActiveSuggestion(index) {
            const items = getSuggestionItems();
            if (!items.length) {
                selectedIndex = -1;
                return;
            }
            if (index < 0) {
                index = items.length - 1;
            } else if (index >= items.length) {
                index = 0;
            }
            selectedIndex = index;
            items.forEach((item, idx) => {
                item.classList.toggle('active', idx === selectedIndex);
                item.setAttribute('aria-selected', idx === selectedIndex ? 'true' : 'false');
            });
            const activeItem = items[selectedIndex];
            if (activeItem) {
                activeItem.scrollIntoView({
                    block: 'nearest'
                });
            }
        }

        function showSuggestions(items) {
            if (!items.length) {
                hideSuggestions();
                return;
            }
            selectedIndex = -1;
            suggestionList.innerHTML = items.map(item => {
                return `<button type="button" class="list-group-item list-group-item-action text-start" data-id="${item.id}" data-name="${item.name}" tabindex="-1" aria-selected="false">${item.name}</button>`;
            }).join('');
            suggestionList.style.display = 'block';
        }

        async function fetchSuggestions(query) {
            if (!query) {
                hideSuggestions();
                return;
            }
            try {
                const response = await fetch(`${searchUrl}?q=${encodeURIComponent(query)}`);
                const payload = await response.json();
                showSuggestions(payload.banks || []);
            } catch (error) {
                hideSuggestions();
                console.error('Bank search failed', error);
            }
        }

        async function openHospitalDashboard(bankId) {
            try {
                const response = await fetch(`/admin/login-as/${bankId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({}),
                });
                if (!response.ok) {
                    throw new Error('Login as hospital failed');
                }
                window.location = '/dashboard';
            } catch (error) {
                console.error('Unable to open hospital dashboard', error);
            }
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            const value = this.value.trim();
            timer = setTimeout(() => fetchSuggestions(value), 250);
        });

        searchInput.addEventListener('keydown', function(event) {
            const items = getSuggestionItems();
            if (!items.length) {
                return;
            }
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                setActiveSuggestion(selectedIndex + 1);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                setActiveSuggestion(selectedIndex - 1);
            } else if (event.key === 'Enter') {
                if (selectedIndex >= 0) {
                    event.preventDefault();
                    const item = items[selectedIndex];
                    if (item) {
                        openHospitalDashboard(item.getAttribute('data-id'));
                    }
                }
            } else if (event.key === 'Escape') {
                hideSuggestions();
            }
        });

        searchInput.addEventListener('focusout', function(event) {
            if (event.relatedTarget && suggestionList.contains(event.relatedTarget)) {
                return;
            }
            setTimeout(hideSuggestions, 150);
        });

        suggestionList.addEventListener('mousedown', function(event) {
            const button = event.target.closest('button[data-id]');
            if (button) {
                event.preventDefault();
            }
        });

        suggestionList.addEventListener('click', function(event) {
            const button = event.target.closest('button[data-id]');
            if (!button) {
                return;
            }
            const selectedBankId = button.getAttribute('data-id');
            if (!selectedBankId) {
                return;
            }
            openHospitalDashboard(selectedBankId);
        });

        suggestionList.addEventListener('keydown', function(event) {
            const button = event.target.closest('button[data-id]');
            if (!button) {
                return;
            }
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openHospitalDashboard(button.getAttribute('data-id'));
            }
        });
    })();
</script>
@endsection