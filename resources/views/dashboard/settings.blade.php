@extends('layouts.dashboard')

@section('title', 'Pengaturan Toko')

@section('content')
<h4 class="fw-bold mb-4">Pengaturan Toko</h4>

<form action="{{ route('dashboard.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Informasi Toko</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Toko <span class="text-danger">*</span></label>
                        <input type="text" name="store_name" class="form-control @error('store_name') is-invalid @enderror"
                            value="{{ old('store_name', auth()->user()->store_name) }}" required>
                        @error('store_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Toko</label>
                        <textarea name="store_description" class="form-control @error('store_description') is-invalid @enderror"
                            rows="4">{{ old('store_description', auth()->user()->store_description) }}</textarea>
                        @error('store_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', auth()->user()->address) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kota</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', auth()->user()->city) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="province" class="form-control" value="{{ old('province', auth()->user()->province) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kode Pos</label>
                            <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', auth()->user()->postal_code) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Logo Toko</div>
                <div class="card-body text-center">
                    @if(auth()->user()->store_logo)
                    <img src="{{ asset('storage/' . auth()->user()->store_logo) }}"
                        class="rounded mb-3" width="150" height="150" style="object-fit:cover;">
                    @else
                    <div class="bg-light rounded d-inline-flex align-items-center justify-content-center mb-3"
                        style="width:150px;height:150px;">
                        <i class="fas fa-store fa-3x text-muted"></i>
                    </div>
                    @endif
                    <input type="file" name="store_logo" class="form-control @error('store_logo') is-invalid @enderror" accept="image/*">
                    @error('store_logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i> Simpan Pengaturan</button>
        </div>
    </div>
</form>
@endsection