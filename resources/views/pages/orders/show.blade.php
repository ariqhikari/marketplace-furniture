@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pesanan</a></li>
            <li class="breadcrumb-item active">{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            {{-- Order Items --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between">
                    <span class="fw-bold">Item Pesanan</span>
                    <span class="badge bg-{{ $order->statusBadge }}">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->primaryImage)
                                        <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}"
                                            width="50" height="50" class="rounded me-2" style="object-fit:cover;">
                                        @endif
                                        <div>
                                            <span class="fw-semibold">{{ $item->product->name ?? 'Produk dihapus' }}</span>
                                            <small class="text-muted d-block">Penjual: {{ $item->seller->store_name ?? $item->seller->name ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ format_rupiah($item->price) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="fw-semibold">{{ format_rupiah($item->subtotal) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total</td>
                                <td class="fw-bold text-primary">{{ format_rupiah($order->total_price) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Cancel Button --}}
            @if($order->status === 'PENDING')
            <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times me-1"></i> Batalkan Pesanan
                </button>
            </form>
            @endif
        </div>

        <div class="col-md-4">
            {{-- Shipping Info --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Informasi Pengiriman</div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $order->shipping_name }}</strong></p>
                    <p class="mb-1">{{ $order->shipping_phone }}</p>
                    <p class="mb-1">{{ $order->shipping_address }}</p>
                    <p class="mb-0">{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Pembayaran</div>
                <div class="card-body">
                    <p class="mb-1">Metode: <strong>Transfer Bank</strong></p>
                    @if($order->bank)
                    <p class="mb-1">Bank: <strong>{{ $order->bank->bank_name }}</strong></p>
                    <p class="mb-1">No. Rekening: <strong>{{ $order->bank->account_number }}</strong></p>
                    <p class="mb-1">Atas Nama: <strong>{{ $order->bank->account_holder }}</strong></p>
                    @endif
                    <p class="mb-0">Status: <span class="badge bg-{{ $order->statusBadge }}">{{ ucfirst($order->status) }}</span></p>

                    @if($order->payment_proof)
                    <hr>
                    <p class="mb-1 fw-semibold">Bukti Transfer:</p>
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" class="img-fluid rounded" style="max-height:200px;">
                    </a>
                    @endif
                </div>
            </div>

            {{-- Upload Bukti Transfer --}}
            @if($order->status === 'PENDING' && !$order->payment_proof)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Upload Bukti Transfer</div>
                <div class="card-body">
                    <form action="{{ route('orders.uploadPaymentProof', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Pilih foto bukti transfer <span class="text-danger">*</span></label>
                            <input type="file" name="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/jpg" required>
                            @error('payment_proof') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Format: JPG, JPEG, PNG. Maks: 2MB</small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-1"></i> Upload Bukti Transfer
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Order Info --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Info Pesanan</div>
                <div class="card-body">
                    <p class="mb-1">No. Pesanan: <strong>{{ $order->order_number }}</strong></p>
                    <p class="mb-1">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                    @if($order->notes)
                    <p class="mb-0">Catatan: {{ $order->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection