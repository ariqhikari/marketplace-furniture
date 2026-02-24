@extends('layouts.dashboard')

@section('title', 'Produk Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Produk Saya</h4>
    <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Produk
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="70">Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                            width="50" height="50" class="rounded" style="object-fit:cover;">
                        @else
                        <img src="https://placehold.co/50x50?text=N" width="50" height="50" class="rounded">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none fw-semibold">
                            {{ Str::limit($product->name, 30) }}
                        </a>
                    </td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ format_rupiah($product->price) }}</td>
                    <td>
                        <span class="{{ $product->stock < 5 ? 'text-danger fw-bold' : '' }}">{{ $product->stock }}</span>
                    </td>
                    <td>
                        @if($product->is_active)
                        <span class="badge bg-success">Aktif</span>
                        @else
                        <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('dashboard.products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        Belum ada produk. <a href="{{ route('dashboard.products.create') }}">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($products->hasPages())
<div class="mt-3">{{ $products->links() }}</div>
@endif
@endsection