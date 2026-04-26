<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'CBMS') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-8RnN9bkBJJWwXV0t93k3V99eQBU92Vwzcqfxw6vR9iS5GjXgGB4v4QxraYhEz6qC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.4/css/all.min.css" integrity="sha512-sWcT1oyNV3QK9XNVc1w1sHJe7Hin+xYddeT5Dz7xvLWyZ3JJRf4/VgUkfIkkx5bk4D5yGM6+M0xAqU6FTZg8IQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        @guest
            <div class="auth-shell">
                <div class="w-full w-md-450px mx-auto">
                    <div class="text-center mb-5">
                        <a href="{{ url('/') }}" class="text-decoration-none">
                            <h1 class="h3 mb-2 app-brand brand">{{ config('app.name', 'CBMS') }}</h1>
                            <p class="text-muted mb-0">A modern blood management interface with authentication-ready pages.</p>
                        </a>
                    </div>

                    <div class="card shadow-sm rounded-4 border-0">
                        <div class="card-body p-5">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="app-shell">
                <aside class="app-sidebar">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <a href="{{ route('dashboard') }}" class="d-inline-block brand text-decoration-none">{{ config('app.name', 'CBMS') }}</a>
                            <p class="text-muted small mb-0">Blood management dashboard</p>
                        </div>
                    </div>

                    <nav class="mb-4">
                        <a href="{{ route('dashboard') }}" class="active"><i class="fa-solid fa-house me-2"></i>Dashboard</a>
                        @if(Route::has('profile.edit'))
                            <a href="{{ route('profile.edit') }}"><i class="fa-solid fa-user me-2"></i>Profile</a>
                        @endif
                        <a href="{{ url('/')}}"><i class="fa-solid fa-globe me-2"></i>Homepage</a>
                    </nav>

                    <div class="mt-auto pt-4 border-top">
                        <h6 class="text-uppercase text-muted fs-7 mb-3">Quick actions</h6>
                        <a href="{{ route('dashboard') }}" class="text-decoration-none d-flex align-items-center gap-2 text-muted">
                            <i class="fa-solid fa-chart-line"></i>
                            <span>View reports</span>
                        </a>
                    </div>
                </aside>

                <div>
                    <div class="app-topbar">
                        <div>
                            @isset($header)
                                <div class="mb-0">
                                    {{ $header }}
                                </div>
                            @else
                                <h2 class="h5 mb-0">Welcome back, {{ Auth::user()->name }}</h2>
                                <p class="text-muted mb-0">Manage your blood center operations from one place.</p>
                            @endisset
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <div class="d-none d-lg-flex align-items-center gap-3 px-3 py-2 rounded-3 border border-muted bg-white">
                                <i class="fa-solid fa-bell text-secondary"></i>
                                <span class="text-secondary">No alerts</span>
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm rounded-pill dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="me-2 badge rounded-circle bg-primary text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userMenuButton">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Log out</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <main class="app-content">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        @endguest

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-Ff8Hskj+NY0ZABc/2bQViifltc2voK7WIK9h48aHVI7hnRZB0j+4oP3tZk6w7/iq" crossorigin="anonymous"></script>
        @stack('scripts')
    </body>
</html>
