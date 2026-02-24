@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 my-5">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3><i class="fas fa-couch text-primary me-2"></i>FurniShop</h3>
                        <p class="text-muted">Buat akun baru</p>
                    </div>

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Daftar Sebagai <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role" id="roleUser"
                                        value="USER" {{ old('role', 'USER') === 'USER' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="roleUser">
                                        <i class="fas fa-user me-1"></i> Pembeli
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role" id="roleSeller"
                                        value="SELLER" {{ old('role') === 'SELLER' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="roleSeller">
                                        <i class="fas fa-store me-1"></i> Penjual
                                    </label>
                                </div>
                            </div>
                            @error('role')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        {{-- Seller-specific fields --}}
                        <div id="sellerFields" style="display: none;">
                            <div class="mb-3">
                                <label for="store_name" class="form-label">Nama Toko <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('store_name') is-invalid @enderror"
                                    id="store_name" name="store_name" value="{{ old('store_name') }}">
                                @error('store_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="store_description" class="form-label">Deskripsi Toko</label>
                                <textarea class="form-control" id="store_description" name="store_description" rows="2">{{ old('store_description') }}</textarea>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </button>

                        <div class="text-center">
                            <p class="text-muted">Sudah punya akun?
                                <a href="{{ route('login') }}" class="text-decoration-none">Login</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle seller fields based on role selection
    document.querySelectorAll('input[name="role"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.getElementById('sellerFields').style.display =
                this.value === 'SELLER' ? 'block' : 'none';
        });
    });
    // Initialize on page load
    if (document.querySelector('input[name="role"]:checked')?.value === 'SELLER') {
        document.getElementById('sellerFields').style.display = 'block';
    }
</script>
@endpush