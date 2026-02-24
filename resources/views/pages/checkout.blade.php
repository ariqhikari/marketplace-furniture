@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="fas fa-credit-card me-2"></i>Checkout</h4>


    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">Alamat Pengiriman</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_name" class="form-control @error('shipping_name') is-invalid @enderror"
                                value="{{ old('shipping_name', auth()->user()->name) }}" required>
                            @error('shipping_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror"
                                rows="3" required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                            @error('shipping_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kota <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_city" class="form-control @error('shipping_city') is-invalid @enderror"
                                    value="{{ old('shipping_city', auth()->user()->city) }}" required>
                                @error('shipping_city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_province" class="form-control @error('shipping_province') is-invalid @enderror"
                                    value="{{ old('shipping_province', auth()->user()->province) }}" required>
                                @error('shipping_province') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kode Pos <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_postal_code" class="form-control @error('shipping_postal_code') is-invalid @enderror"
                                    value="{{ old('shipping_postal_code', auth()->user()->postal_code) }}" required>
                                @error('shipping_postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_phone" class="form-control @error('shipping_phone') is-invalid @enderror"
                                value="{{ old('shipping_phone', auth()->user()->phone) }}" required>
                            @error('shipping_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">Metode Pembayaran — Transfer Bank</div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Pilih rekening bank tujuan transfer:</p>
                        @foreach($banks as $bank)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="bank_id" value="{{ $bank->id }}"
                                id="bank_{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'checked' : ($loop->first && !old('bank_id') ? 'checked' : '') }} required>
                            <label class="form-check-label" for="bank_{{ $bank->id }}">
                                <strong>{{ $bank->bank_name }}</strong> — {{ $bank->account_number }}
                                <small class="text-muted d-block">a.n. {{ $bank->account_holder }}</small>
                            </label>
                        </div>
                        @endforeach
                        @error('bank_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold">Ringkasan Pesanan</div>
                    <div class="card-body">
                        @foreach($carts as $cart)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="fw-semibold">{{ Str::limit($cart->product->name, 30) }}</span>
                                <small class="text-muted d-block">{{ $cart->quantity }} x {{ format_rupiah($cart->product->price) }}</small>
                            </div>
                            <span>{{ format_rupiah($cart->subtotal) }}</span>
                        </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>{{ format_rupiah($grandTotal) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ongkos Kirim</span>
                            <span class="text-success">Gratis</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total</span>
                            <span class="text-primary">{{ format_rupiah($grandTotal) }}</span>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-3">
                            <i class="fas fa-check me-1"></i> Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection