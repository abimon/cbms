<!-- Navigation -->
<div class="">
    <div class="top-navbar">
        <!-- navigation -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="log.png" alt="CBMS Logo" height="30">
                    Centralized Blood Management System
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ auth()->user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="bi bi-person-circle me-2"></i>Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('profile.password') }}">
                                    <i class="bi bi-shield-lock me-2"></i>Change Password
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="sidebar">
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link {{ request()->path()=='dashboard'?'active':'' }}" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <div class="sidebar-section">
                <h6 class="sidebar-section-title">Management</h6>
            </div>
            <li class="nav-item">
                <a class="nav-link {{ request()->path()=='banks/*'?'active':'' }}" href="{{ route('banks.index') }}">Blood Banks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->path()=='inventories/*'?'active':'' }}" href="{{ route('inventories.index') }}">Blood Inventories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->path()=='requests/*'?'active':'' }}" href="{{ route('requests.index') }}">Blood Requests</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->path()=='withdrawals/
                *'?'active':'' }}" href="{{ route('withdrawals.index') }}">Withdrawals</a>
            </li>
        </ul>
    </div>
</div>