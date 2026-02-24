@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="fas fa-receipt me-2"></i>Pesanan Saya</h4>


    @if($orders->isNotEmpty())
    @foreach($orders as $order)
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-bold">{{ $order->order_number }}</span>
                <small class="text-muted ms-2">{{ $order->created_at->format('d M Y, H:i') }}</small>
            </div>
            <span class="badge bg-{{ $order->statusBadge }}">{{ ucfirst($order->status) }}</span>
        </div>
        <div class="card-body">
            @foreach($order->orderItems->take(3) as $item)
            <div class="d-flex align-items-center mb-2">
                @if($item->product && $item->product->primaryImage)
                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}"
                    width="50" height="50" class="rounded me-3" style="object-fit:cover;">
                @else
                <img src="https://placehold.co/50x50?text=N" width="50" height="50" class="rounded me-3">
                @endif
                <div class="flex-grow-1">
                    <span class="fw-semibold">{{ $item->product->name ?? 'Produk tidak tersedia' }}</span>
                    <small class="text-muted d-block">{{ $item->quantity }} x {{ format_rupiah($item->price) }}</small>
                </div>
                <span>{{ format_rupiah($item->subtotal) }}</span>
            </div>
            @endforeach
            @if($order->orderItems->count() > 3)
            <small class="text-muted">+{{ $order->orderItems->count() - 3 }} item lainnya</small>
            @endif
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <span>Total: <strong class="text-primary">{{ format_rupiah($order->total_price) }}</strong></span>
            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-eye me-1"></i>Detail
            </a>
        </div>
    </div>
    @endforeach

    <div class="mt-3">{{ $orders->links() }}</div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
        <h5>Belum ada pesanan</h5>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Belanja Sekarang</a>
    </div>
    @endif
</div>
@endsection