@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<h4 class="fw-bold mb-4">Dashboard</h4>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Users</small>
                        <h4 class="fw-bold mb-0">{{ $totalUsers }}</h4>
                    </div>
                    <i class="fas fa-users fa-2x text-primary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Produk</small>
                        <h4 class="fw-bold mb-0">{{ $totalProducts }}</h4>
                    </div>
                    <i class="fas fa-box fa-2x text-success opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Pesanan</small>
                        <h4 class="fw-bold mb-0">{{ $totalOrders }}</h4>
                    </div>
                    <i class="fas fa-shopping-cart fa-2x text-warning opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm border-start border-danger border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Pendapatan</small>
                        <h4 class="fw-bold mb-0">{{ format_rupiah($totalRevenue) }}</h4>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x text-danger opacity-50"></i>
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
                            <th>Pembeli</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td><small>{{ $order->order_number }}</small></td>
                            <td>{{ $order->user->name ?? '-' }}</td>
                            <td>{{ format_rupiah($order->total_price) }}</td>
                            <td><span class="badge bg-{{ $order->statusBadge }}">{{ ucfirst($order->status) }}</span></td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
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

    {{-- Quick Stats --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Statistik</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><span>Seller</span><strong>{{ $usersByRole['seller'] }}</strong></div>
                <div class="d-flex justify-content-between mb-2"><span>Buyer</span><strong>{{ $usersByRole['user'] }}</strong></div>
                <div class="d-flex justify-content-between"><span>Pesanan Pending</span><strong class="text-warning">{{ $ordersByStatus['pending'] }}</strong></div>
            </div>
        </div>
    </div>
</div>
@endsection