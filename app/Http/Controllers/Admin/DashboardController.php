<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

/**
 * Admin Dashboard — Statistics
 */
class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalUsers    = User::count();
        $totalOrders   = Order::count();
        $totalRevenue  = Order::where('status', 'DELIVERED')->sum('total_price');

        $usersByRole = [
            'admin'  => User::where('role', 'ADMIN')->count(),
            'seller' => User::where('role', 'SELLER')->count(),
            'user'   => User::where('role', 'USER')->count(),
        ];

        $ordersByStatus = [
            'pending'    => Order::where('status', 'PENDING')->count(),
            'processing' => Order::where('status', 'PROCESSING')->count(),
            'shipped'    => Order::where('status', 'SHIPPED')->count(),
            'delivered'  => Order::where('status', 'DELIVERED')->count(),
            'cancelled'  => Order::where('status', 'CANCELLED')->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(10)->get();

        $topProducts = Product::withCount(['orderItems as sold_count' => fn($q) => $q->where('status', 'DELIVERED')])
            ->orderByDesc('sold_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'usersByRole',
            'ordersByStatus',
            'recentOrders',
            'topProducts'
        ));
    }
}
