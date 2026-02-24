{{-- Navbar Component --}}
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="fas fa-couch me-2"></i>FurniShop
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            {{-- Search Bar --}}
            <form action="{{ route('products.search') }}" method="GET" class="d-flex mx-auto" style="max-width:400px;">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Cari furniture..." value="{{ request('q') }}">
                    <button class="btn btn-warning" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}">
                        <i class="fas fa-th-large me-1"></i> Produk
                    </a>
                </li>

                @auth
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        @php $cartCount = auth()->user()->carts()->count(); @endphp
                        @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark" style="font-size:.65rem;">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @if(auth()->user()->isAdmin())
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-shield-alt me-2"></i>Admin Panel</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @endif
                        @if(auth()->user()->isSeller() || auth()->user()->isAdmin())
                        <li><a class="dropdown-item" href="{{ route('dashboard.index') }}"><i class="fas fa-store me-2"></i>Seller Dashboard</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-clipboard-list me-2"></i>Pesanan Saya</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-edit me-2"></i>Profil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-warning btn-sm ms-2" href="{{ route('register') }}">Register</a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>