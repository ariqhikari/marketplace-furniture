@extends('layouts.dashboard')

@section('title', 'Dashboard Seller')

@section('content')
<h4 class="fw-bold mb-4">Dashboard</h4>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Produk</small>
                        <h4 class="fw-bold mb-0">{{ $totalProducts }}</h4>
                    </div>
                    <i class="fas fa-box fa-2x text-primary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Penjualan</small>
                        <h4 class="fw-bold mb-0">{{ $totalSales }}</h4>
                    </div>
                    <i class="fas fa-shopping-bag fa-2x text-success opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Pendapatan</small>
                        <h4 class="fw-bold mb-0">{{ format_rupiah($totalRevenue) }}</h4>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x text-warning opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-danger border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Pesanan Pending</small>
                        <h4 class="fw-bold mb-0">{{ $pendingOrders }}</h4>
                    </div>
                    <i class="fas fa-clock fa-2x text-danger opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Recent Orders --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Pesanan Terbaru</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order</th>
                            <th>Produk</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $item)
                        <tr>
                            <td><small>{{ $item->order->order_number ?? '-' }}</small></td>
                            <td>{{ Str::limit($item->product->name ?? '-', 25) }}</td>
                            <td>{{ format_rupiah($item->subtotal) }}</td>
                            <td><span class="badge bg-{{ $item->statusBadge }}">{{ ucfirst($item->status) }}</span></td>
                            <td>
                                <a href="{{ route('dashboard.orders.show', $item->order_id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada pesanan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Produk Terlaris</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Terjual</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                        <tr>
                            <td>{{ Str::limit($product->name, 20) }}</td>
                            <td>{{ $product->sold_count ?? 0 }}</td>
                            <td>
                                <span class="{{ $product->stock < 5 ? 'text-danger' : '' }}">{{ $product->stock }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">Belum ada produk</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection