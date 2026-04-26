<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CBMS - Blood Management') }}</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #dc3545;
            --primary-dark: #b02a37;
            --primary-light: #f8d7da;
            --secondary: #6c757d;
            --success: #198754;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #0dcaf0;
            --dark: #212529;
            --light: #f8f9fa;
            --sidebar-width: 260px;
            --sidebar-collapsed: 70px;
            --topbar-height: 60px;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f5f6fa;
            overflow-x: hidden;
        }

        .top-navbar {
            background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
            height: var(--topbar-height);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
        }

        .top-navbar .navbar-brand {
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .top-navbar .nav-link {
            color: rgba(255, 255, 255, 0.9);
        }

        .top-navbar .nav-link:hover {
            color: white;
        }

        .sidebar {
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--topbar-height));
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            z-index: 999;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar .nav-link {
            color: #495057;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .sidebar .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .sidebar .nav-text {
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
        }

        .sidebar-section {
            padding: 15px 20px 5px;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #adb5bd;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 25px;
            min-height: calc(100vh - var(--topbar-height));
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 15px 20px;
            font-weight: 600;
            border-radius: 12px 12px 0 0 !important;
        }

        .stat-card {
            border-radius: 12px;
            padding: 20px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            right: -20px;
            top: -20px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.2;
        }

        .stat-card.primary {
            background: linear-gradient(135deg, #dc3545, #b02a37);
        }

        .stat-card.success {
            background: linear-gradient(135deg, #198754, #146c43);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #ffc107, #ffca2c);
        }

        .stat-card.info {
            background: linear-gradient(135deg, #0dcaf0, #31d2f2);
        }

        .stat-card .stat-icon {
            font-size: 2.5rem;
            opacity: 0.3;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
        }

        .stat-card .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #495057;
            padding: 12px 15px;
        }

        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-available {
            background: #d1e7dd;
            color: #0f5132;
        }

        .badge-pending {
            background: #fff3cd;
            color: #664d03;
        }

        .badge-approved {
            background: #cfe2ff;
            color: #084298;
        }

        .badge-rejected {
            background: #f8d7da;
            color: #842029;
        }

        .badge-fulfilled {
            background: #d1e7dd;
            color: #0f5132;
        }

        .badge-expired {
            background: #e2e3e5;
            color: #41464b;
        }

        .badge-tested {
            background: #d1e7dd;
            color: #0f5132;
        }

        .badge-not-tested {
            background: #fff3cd;
            color: #664d03;
        }

        .blood-type {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .blood-type-a {
            background: #ffe5d9;
            color: #c44d2e;
        }

        .blood-type-b {
            background: #d9e4ff;
            color: #2e5ec4;
        }

        .blood-type-ab {
            background: #e8d5ff;
            color: #6e2ec4;
        }

        .blood-type-o {
            background: #d4f4dd;
            color: #2ec46e;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 10px 15px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 6px;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #dc3545 0%, #b02a37 50%, #212529 100%);
        }

        .auth-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }

        .auth-header {
            background: linear-gradient(135deg, #dc3545, #b02a37);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .auth-header h2 {
            margin: 0;
            font-weight: 700;
        }

        .auth-header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }

        .auth-body {
            padding: 30px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #212529;
            margin: 0;
        }

        .page-subtitle {
            color: #6c757d;
            margin: 5px 0 0;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.expanded {
                margin-left: 0;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 15px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .stat-card {
                margin-bottom: 15px;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease forwards;
        }

        .user-dropdown {
            position: relative;
        }

        .user-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            z-index: 1001;
            display: none;
        }

        .user-menu.show {
            display: block;
        }

        .user-menu a {
            display: block;
            padding: 12px 20px;
            color: #495057;
            text-decoration: none;
            transition: background 0.2s;
        }

        .user-menu a:hover {
            background: #f8f9fa;
        }

        .user-menu a i {
            margin-right: 10px;
            width: 20px;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

        <!-- Page Content -->
            @guest
            @yield('content')
            @else
            @include('layouts.navigation')
            <div class="main-content">
                @yield('content')
            </div>
            @endguest
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>

</body>

</html>