@extends('layouts.dashboard')

@section('title', 'Detail Pesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Detail Pesanan {{ $order->order_number }}</h4>
    <a href="{{ route('dashboard.orders.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Item Pesanan</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->product->name ?? '-' }}</td>
                            <td>{{ format_rupiah($item->price) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ format_rupiah($item->subtotal) }}</td>
                            <td><span class="badge bg-{{ $item->statusBadge }}">{{ ucfirst($item->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td colspan="2" class="fw-bold text-primary">{{ format_rupiah($order->total_price) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Update Status per Item --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Update Status Pesanan</div>
            <div class="card-body">
                @foreach($order->orderItems->where('seller_id', auth()->id()) as $item)
                <form action="{{ route('dashboard.orders.updateStatus', $item->id) }}" method="POST" class="d-flex gap-2 align-items-center mb-2">
                    @csrf @method('PATCH')
                    <small class="text-muted" style="min-width:150px;">{{ Str::limit($item->product->name ?? '-', 20) }}</small>
                    <select name="status" class="form-select form-select-sm" style="width:auto;">
                        @foreach(['PENDING','PROCESSING','SHIPPED','DELIVERED','CANCELLED'] as $status)
                        <option value="{{ $status }}" {{ $item->status === $status ? 'selected' : '' }}>
                            {{ ucfirst(strtolower($status)) }}
                        </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                </form>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Pembeli</div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $order->user->name ?? '-' }}</strong></p>
                <p class="mb-0">{{ $order->user->email ?? '-' }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Pengiriman</div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $order->shipping_name }}</strong></p>
                <p class="mb-1">{{ $order->shipping_phone }}</p>
                <p class="mb-1">{{ $order->shipping_address }}</p>
                <p class="mb-0">{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Pembayaran</div>
            <div class="card-body">
                <p class="mb-1">Metode: <strong>Transfer Bank</strong></p>
                @if($order->bank)
                <p class="mb-1">Bank: <strong>{{ $order->bank->bank_name }}</strong></p>
                <p class="mb-1">No. Rek: <strong>{{ $order->bank->account_number }}</strong></p>
                @endif
                <p class="mb-1">Status: <span class="badge bg-{{ $order->statusBadge }}">{{ ucfirst($order->status) }}</span></p>
                <p class="mb-0">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                @if($order->payment_proof)
                <hr>
                <p class="mb-1 fw-semibold">Bukti Transfer:</p>
                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                    <img src="{{ asset('storage/' . $order->payment_proof) }}" class="img-fluid rounded" style="max-height:200px;">
                </a>
                @endif
                @if($order->notes)
                <hr>
                <p class="mb-0"><strong>Catatan:</strong> {{ $order->notes }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection