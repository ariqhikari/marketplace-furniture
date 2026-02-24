@extends('layouts.dashboard')

@section('title', 'Pesanan Masuk')

@section('content')
<h4 class="fw-bold mb-4">Pesanan Masuk</h4>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard.orders.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari order atau pembeli..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="PROCESSING" {{ request('status') == 'PROCESSING' ? 'selected' : '' }}>Processing</option>
                    <option value="SHIPPED" {{ request('status') == 'SHIPPED' ? 'selected' : '' }}>Shipped</option>
                    <option value="DELIVERED" {{ request('status') == 'DELIVERED' ? 'selected' : '' }}>Delivered</option>
                    <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> Cari</button>
                @if(request('search') || request('status'))
                <a href="{{ route('dashboard.orders.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </div>
        </form>

        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th width="50">#</th>
                    <th>Order</th>
                    <th>Pembeli</th>
                    <th>Total</th>
                    <th>Bank</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                    <td><small>{{ $order->order_number }}</small></td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td>{{ format_rupiah($order->total_price) }}</td>
                    <td>{{ $order->bank->bank_name ?? '-' }}</td>
                    <td><span class="badge bg-{{ $order->statusBadge }}">{{ ucfirst($order->status) }}</span></td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('dashboard.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">Belum ada pesanan masuk</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection