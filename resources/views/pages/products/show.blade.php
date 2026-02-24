@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
            @if($product->category)
            <li class="breadcrumb-item"><a href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Product Images --}}
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @php $images = $product->productImages; @endphp
                    @if($images->isNotEmpty())
                    <img id="mainImage" src="{{ asset('storage/' . $images->first()->image_path) }}"
                        class="img-fluid rounded mb-3 w-100" alt="{{ $product->name }}"
                        style="max-height: 400px; object-fit: cover;">
                    @if($images->count() > 1)
                    <div class="row g-2">
                        @foreach($images as $img)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                class="img-fluid rounded border thumbnail-img"
                                style="cursor:pointer; height:80px; width:100%; object-fit:cover;"
                                onclick="document.getElementById('mainImage').src=this.src">
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @else
                    <img src="https://placehold.co/600x400?text=No+Image" class="img-fluid rounded w-100" alt="No Image">
                    @endif
                </div>
            </div>
        </div>

        {{-- Product Info --}}
        <div class="col-md-6 mb-4">
            <h2 class="fw-bold">{{ $product->name }}</h2>
            <div class="d-flex align-items-center mb-2">
                <span class="badge bg-secondary me-2">{{ $product->category->name ?? '-' }}</span>
            </div>

            <h3 class="text-primary fw-bold my-3">{{ format_rupiah($product->price) }}</h3>

            <div class="mb-3">
                <span class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                    <i class="fas {{ $product->stock > 0 ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                    {{ $product->stock > 0 ? 'Stok: ' . $product->stock : 'Stok Habis' }}
                </span>
            </div>

            {{-- Specifications --}}
            <table class="table table-sm table-bordered mb-3">
                <tbody>
                    @if($product->material)
                    <tr>
                        <th width="35%">Material</th>
                        <td>{{ $product->material }}</td>
                    </tr>
                    @endif
                    @if($product->color)
                    <tr>
                        <th>Warna</th>
                        <td>{{ $product->color }}</td>
                    </tr>
                    @endif
                    @if($product->dimensions)
                    <tr>
                        <th>Dimensi</th>
                        <td>{{ $product->dimensions }}</td>
                    </tr>
                    @endif
                    @if($product->weight)
                    <tr>
                        <th>Berat</th>
                        <td>{{ $product->weight }} kg</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            {{-- Seller Info --}}
            @if($product->user)
            <div class="card border-0 bg-light mb-3">
                <div class="card-body py-2">
                    <small class="text-muted">Dijual oleh</small>
                    <div class="fw-semibold">
                        <i class="fas fa-store me-1 text-primary"></i>{{ $product->user->store_name ?? $product->user->name }}
                    </div>
                </div>
            </div>
            @endif

            {{-- Add to Cart --}}
            @auth
            @if($product->stock > 0)
            <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-end gap-2">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div>
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control" style="width:80px;">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-cart-plus me-1"></i> Tambah ke Keranjang
                </button>
            </form>
            @endif
            @else
            <a href="{{ route('login') }}" class="btn btn-primary"><i class="fas fa-sign-in-alt me-1"></i> Login untuk Membeli</a>
            @endauth
        </div>
    </div>

    {{-- Description --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Deskripsi Produk</h5>
            <div>{!! nl2br(e($product->description)) !!}</div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->isNotEmpty())
    <h5 class="fw-bold mb-3">Produk Terkait</h5>
    <div class="row g-3">
        @foreach($relatedProducts as $related)
        <div class="col-6 col-md-3">
            @include('components.product-card', ['product' => $related])
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection