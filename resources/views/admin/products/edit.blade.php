@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<h4 class="fw-bold mb-4">Edit Produk (Admin)</h4>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Informasi Produk</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $product->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ $product->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Info Seller</div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $product->user->name ?? '-' }}</strong></p>
                    <p class="mb-0 text-muted">{{ $product->user->store_name ?? '-' }}</p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i> Update Produk</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100 mt-2">Kembali</a>
        </div>
    </div>
</form>
@endsection