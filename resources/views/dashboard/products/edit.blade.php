@extends('layouts.dashboard')

@section('title', 'Edit Produk')

@section('content')
<h4 class="fw-bold mb-4">Edit Produk</h4>

<form action="{{ route('dashboard.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
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
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $product->price) }}" min="0" required>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                value="{{ old('stock', $product->stock) }}" min="0" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Berat (kg)</label>
                            <input type="number" name="weight" step="0.01" class="form-control"
                                value="{{ old('weight', $product->weight) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ old('is_active', $product->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $product->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                            rows="4" required>{{ old('description', $product->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Existing Images --}}
            @if($product->productImages->isNotEmpty())
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Gambar Saat Ini</div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach($product->productImages as $image)
                        <div class="col-3 position-relative">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                class="img-fluid rounded border" style="height:100px; width:100%; object-fit:cover;">
                            <div class="form-check mt-1">
                                <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="form-check-input">
                                <label class="form-check-label small text-danger">Hapus</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Spesifikasi</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Material</label>
                        <input type="text" name="material" class="form-control" value="{{ old('material', $product->material) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warna</label>
                        <input type="text" name="color" class="form-control" value="{{ old('color', $product->color) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dimensi</label>
                        <input type="text" name="dimensions" class="form-control" value="{{ old('dimensions', $product->dimensions) }}">
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Tambah Gambar Baru</div>
                <div class="card-body">
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    <small class="text-muted">Maks 5 gambar, format: JPG, PNG (maks 2MB/gambar)</small>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i> Update Produk</button>
            <a href="{{ route('dashboard.products.index') }}" class="btn btn-outline-secondary w-100 mt-2">Batal</a>
        </div>
    </div>
</form>
@endsection