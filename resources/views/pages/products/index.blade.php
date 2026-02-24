@extends('layouts.app')

@section('title', isset($category) ? $category->name : 'Semua Produk')

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            @if(isset($category))
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
            @else
            <li class="breadcrumb-item active">Semua Produk</li>
            @endif
        </ol>
    </nav>

    <div class="row">
        {{-- Filter Sidebar --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-filter me-2"></i>Filter</h6>
                    <form action="{{ route('products.index') }}" method="GET">
                        {{-- Category Filter --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            @foreach($categories as $cat)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category"
                                    value="{{ $cat->id }}" id="cat{{ $cat->id }}"
                                    {{ request('category') == $cat->id ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat{{ $cat->id }}">
                                    {{ $cat->name }} <small class="text-muted">({{ $cat->products_count }})</small>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        {{-- Price Range --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga</label>
                            <input type="number" name="min_price" class="form-control form-control-sm mb-2"
                                placeholder="Harga minimum" value="{{ request('min_price') }}">
                            <input type="number" name="max_price" class="form-control form-control-sm"
                                placeholder="Harga maksimum" value="{{ request('max_price') }}">
                        </div>

                        {{-- Material Filter --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Material</label>
                            @foreach(['Kayu Jati', 'Metal', 'Rotan', 'Fabric', 'Plastik'] as $mat)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="material"
                                    value="{{ $mat }}" {{ request('material') === $mat ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $mat }}</label>
                            </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search me-1"></i> Terapkan</button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm w-100 mt-2">Reset</a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="mb-0 text-muted">{{ $products->total() }} produk ditemukan</p>
                <div class="d-flex align-items-center">
                    <label class="me-2 text-nowrap">Urutkan:</label>
                    <select class="form-select form-select-sm" onchange="window.location.href=this.value" style="width:auto;">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort','newest')=='newest'?'selected':'' }}>Terbaru</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort')=='price_asc'?'selected':'' }}>Harga Terendah</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort')=='price_desc'?'selected':'' }}>Harga Tertinggi</option>
                    </select>
                </div>
            </div>

            @if($products->isNotEmpty())
            <div class="row g-3">
                @foreach($products as $product)
                <div class="col-6 col-lg-4">
                    @include('components.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h5>Tidak ada produk ditemukan</h5>
                <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Lihat Semua Produk</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection