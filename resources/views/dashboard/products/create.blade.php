@extends('layouts.dashboard')

@section('title', 'Tambah Produk')

@section('content')
<h4 class="fw-bold mb-4">Tambah Produk Baru</h4>

<form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Informasi Produk</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price') }}" min="0" required>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Berat (kg)</label>
                            <input type="number" name="weight" step="0.01" class="form-control"
                                value="{{ old('weight') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                            rows="4" required>{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            {{-- Specifications --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Spesifikasi</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Material</label>
                        <input type="text" name="material" class="form-control" value="{{ old('material') }}" placeholder="Contoh: Kayu Jati">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warna</label>
                        <input type="text" name="color" class="form-control" value="{{ old('color') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dimensi</label>
                        <input type="text" name="dimensions" class="form-control" value="{{ old('dimensions') }}" placeholder="P x L x T cm">
                    </div>
                </div>
            </div>

            {{-- Images --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Gambar Produk</div>
                <div class="card-body">
                    <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror"
                        multiple accept="image/*">
                    <small class="text-muted">Maks 5 gambar, format: JPG, PNG (maks 2MB/gambar)</small>
                    @error('images.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    @error('images') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i> Simpan Produk</button>
            <a href="{{ route('dashboard.products.index') }}" class="btn btn-outline-secondary w-100 mt-2">Batal</a>
        </div>
    </div>
</form>
@endsection