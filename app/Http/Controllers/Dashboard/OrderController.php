<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

/**
 * Seller Order Management
 */
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = auth()->id();

        $query = Order::whereHas('orderItems', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })->with(['user', 'bank', 'orderItems.product'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('dashboard.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $sellerId = auth()->id();

        // Pastikan order ini mengandung item milik seller
        if (!$order->orderItems()->where('seller_id', $sellerId)->exists()) {
            abort(403);
        }

        $order->load(['user', 'bank', 'orderItems.product.productImages', 'orderItems.seller']);

        return view('dashboard.orders.show', compact('order'));
    }

    /**
     */
    public function updateStatus(Request $request, OrderItem $orderItem)
    {
        if ($orderItem->seller_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:PENDING,PROCESSING,SHIPPED,DELIVERED,CANCELLED',
        ]);

        // Prevent reversal from CANCELLED (stock already restored)
        if ($orderItem->status === 'CANCELLED') {
            return back()->with('error', 'Item yang sudah dibatalkan tidak dapat diubah statusnya.');
        }

        // If cancelling, restore stock
        if ($request->status === 'CANCELLED') {
            $orderItem->product->increment('stock', $orderItem->quantity);
        }

        $orderItem->update([
            'status' => $request->status,
        ]);

        // Check if all items have the same status → update order status
        $order = $orderItem->order->load('orderItems');
        $allStatuses = $order->orderItems->pluck('status')->unique();

        if ($allStatuses->count() === 1) {
            $order->update(['status' => $allStatuses->first()]);
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
