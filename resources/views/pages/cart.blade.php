@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja</h4>


    @if($carts->isNotEmpty())
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:100px">Produk</th>
                                <th>Detail</th>
                                <th style="width:130px">Jumlah</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carts as $cart)
                            <tr>
                                <td>
                                    @if($cart->product && $cart->product->primaryImage)
                                    <img src="{{ asset('storage/' . $cart->product->primaryImage->image_path) }}"
                                        class="rounded" width="80" height="80" style="object-fit:cover;">
                                    @else
                                    <img src="https://placehold.co/80x80?text=No+Img" class="rounded" width="80" height="80">
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('products.show', $cart->product->slug ?? '#') }}" class="text-decoration-none fw-semibold">
                                        {{ $cart->product->name ?? 'Produk tidak tersedia' }}
                                    </a>
                                    <div class="text-muted small">{{ format_rupiah($cart->product->price ?? 0) }}</div>
                                </td>
                                <td>
                                    <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="d-flex align-items-center">
                                        @csrf @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $cart->quantity }}"
                                            min="1" max="{{ $cart->product->stock ?? 99 }}" class="form-control form-control-sm" style="width:65px;"
                                            onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td class="fw-semibold">{{ format_rupiah($cart->subtotal) }}</td>
                                <td>
                                    <form action="{{ route('cart.destroy', $cart->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus item ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Ringkasan Belanja</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total ({{ $carts->sum('quantity') }} item)</span>
                        <span class="fw-bold">{{ format_rupiah($total) }}</span>
                    </div>
                    <hr>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100">
                        <i class="fas fa-credit-card me-1"></i> Checkout
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left me-1"></i> Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
        <h5>Keranjang belanja kosong</h5>
        <p class="text-muted">Ayo mulai belanja furniture impian kamu!</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Belanja Sekarang</a>
    </div>
    @endif
</div>
@endsection