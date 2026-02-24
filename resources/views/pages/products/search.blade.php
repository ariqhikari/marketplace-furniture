@extends('layouts.app')

@section('title', 'Hasil Pencarian: ' . $keyword)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Pencarian</li>
        </ol>
    </nav>

    <h4 class="mb-3">Hasil Pencarian: <span class="text-primary">"{{ $keyword }}"</span></h4>
    <p class="text-muted">{{ $products->total() }} produk ditemukan</p>

    @if($products->isNotEmpty())
    <div class="row g-3">
        @foreach($products as $product)
        <div class="col-6 col-md-3">
            @include('components.product-card', ['product' => $product])
        </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-search fa-4x text-muted mb-3"></i>
        <h5>Tidak ada produk yang cocok</h5>
        <p class="text-muted">Coba gunakan kata kunci lain</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Lihat Semua Produk</a>
    </div>
    @endif
</div>
@endsection