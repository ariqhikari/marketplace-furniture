<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

/**
 * Admin Order Management
 */
class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index(Request $request)
    {
        $query = Order::with(['user', 'bank']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'bank', 'orderItems.product.productImages', 'orderItems.seller']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:PENDING,PROCESSING,SHIPPED,DELIVERED,CANCELLED']);

        $previousStatus = $order->status;
        $newStatus = $request->status;

        // Prevent reversal from CANCELLED (stock already restored)
        if ($previousStatus === 'CANCELLED') {
            return back()->with('error', 'Order yang sudah dibatalkan tidak dapat diubah statusnya.');
        }

        if ($newStatus === 'CANCELLED') {
            $this->orderService->cancelOrder($order);
        } else {
            $order->update(['status' => $newStatus]);
            $order->orderItems()->update(['status' => $newStatus]);
        }

        return back()->with('success', 'Status order berhasil diperbarui!');
    }
}
