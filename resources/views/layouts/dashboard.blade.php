<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - FurniShop Seller</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2C3E50;
            --secondary: #E67E22;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background-color: var(--primary);
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, .7);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, .1);
        }

        .sidebar .nav-link i {
            width: 24px;
        }

        .main-content {
            margin-left: 250px;
        }

        .top-navbar {
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
        }
    </style>
    @stack('styles')
</head>

<body>

    {{-- Sidebar --}}
    <div class="sidebar d-flex flex-column p-3">
        <a href="{{ route('home') }}" class="text-white text-decoration-none mb-4 px-3">
            <h5><i class="fas fa-couch me-2"></i>FurniShop</h5>
            <small class="text-white-50">Seller Panel</small>
        </a>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}" href="{{ route('dashboard.index') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.products.*') ? 'active' : '' }}" href="{{ route('dashboard.products.index') }}">
                    <i class="fas fa-box me-2"></i> Produk Saya
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.orders.*') ? 'active' : '' }}" href="{{ route('dashboard.orders.index') }}">
                    <i class="fas fa-shopping-bag me-2"></i> Pesanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.settings.*') ? 'active' : '' }}" href="{{ route('dashboard.settings.edit') }}">
                    <i class="fas fa-cog me-2"></i> Pengaturan Toko
                </a>
            </li>
        </ul>
        <hr class="text-white-50 mt-auto">
        <div class="px-3">
            <a href="{{ route('home') }}" class="nav-link text-white-50"><i class="fas fa-arrow-left me-2"></i> Kembali ke Toko</a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        <nav class="top-navbar navbar navbar-expand-lg px-4 py-3">
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3">{{ auth()->user()->store_name ?? auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </nav>

        <div class="p-4">
            @include('components.alert')
            @yield('content')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>