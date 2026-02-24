@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
{{-- Hero Section --}}
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold">Furniture Berkualitas untuk Rumah Impian Anda</h1>
                <p class="lead mt-3">Temukan berbagai koleksi furniture terbaik dari penjual terpercaya di seluruh Indonesia.</p>
                <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg mt-3">
                    <i class="fas fa-shopping-bag me-2"></i>Belanja Sekarang
                </a>
            </div>
            <div class="col-md-6 text-center mt-2">
                <img src="{{ asset('images/hero.jpg') }}" alt="Hero" class="img-fluid rounded shadow w-75">
            </div>
        </div>
    </div>
</section>

{{-- Categories Section --}}
<section class="py-5">
    <div class="container">
        <h3 class="text-center mb-4"><i class="fas fa-tags me-2"></i>Kategori</h3>
        <div class="row g-3">
            @foreach($categories as $category)
            <div class="col-6 col-md-3">
                <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                    <div class="card text-center border-0 shadow-sm h-100">
                        <div class="card-body">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                                class="rounded mb-2" style="width:80px;height:80px;object-fit:cover;">
                            <h6 class="card-title text-dark">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products_count }} produk</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Latest Products --}}
@if($latestProducts->isNotEmpty())
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="fas fa-clock me-2"></i>Produk Terbaru</h3>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            @foreach($latestProducts as $product)
            <div class="col-6 col-md-3">
                @include('components.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Features Section --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                <h5>Pengiriman Aman</h5>
                <p class="text-muted">Furniture diantar dengan aman ke rumah Anda.</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                <h5>Garansi Produk</h5>
                <p class="text-muted">Semua produk dilengkapi garansi resmi dari seller.</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                <h5>Layanan 24/7</h5>
                <p class="text-muted">Tim support siap membantu kapanpun Anda butuhkan.</p>
            </div>
        </div>
    </div>
</section>
@endsection