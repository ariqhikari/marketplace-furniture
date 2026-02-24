<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

/**
 * Seller Dashboard — Statistics
 */
class DashboardController extends Controller
{
    public function index()
    {
        $sellerId = auth()->id();

        $totalProducts   = Product::where('user_id', $sellerId)->count();
        $totalSales      = OrderItem::where('seller_id', $sellerId)
            ->where('status', 'DELIVERED')
            ->count();
        $totalRevenue    = OrderItem::where('seller_id', $sellerId)
            ->where('status', 'DELIVERED')
            ->sum('subtotal');
        $pendingOrders   = OrderItem::where('seller_id', $sellerId)
            ->where('status', 'PENDING')
            ->count();

        // Recent orders for this seller
        $recentOrders = OrderItem::where('seller_id', $sellerId)
            ->with(['order.user', 'product'])
            ->latest()
            ->take(5)
            ->get();

        $topProducts = Product::where('user_id', $sellerId)
            ->withCount(['orderItems as sold_count' => fn($q) => $q->where('status', 'DELIVERED')])
            ->orderByDesc('sold_count')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalSales',
            'totalRevenue',
            'pendingOrders',
            'recentOrders',
            'topProducts'
        ));
    }
}
