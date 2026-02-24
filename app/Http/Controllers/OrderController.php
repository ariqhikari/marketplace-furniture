<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * OrderController — Buyer order history
 */
class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('orderItems.product')
            ->latest()
            ->paginate(10);

        return view('pages.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['orderItems.product.productImages', 'orderItems.seller', 'bank']);

        return view('pages.orders.show', compact('order'));
    }

    /**
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'PENDING') {
            return back()->with('error', 'Order hanya bisa dibatalkan saat status PENDING.');
        }

        $this->orderService->cancelOrder($order);

        return back()->with('success', 'Order berhasil dibatalkan.');
    }

    /**
     */
    public function uploadPaymentProof(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'PENDING') {
            return back()->with('error', 'Bukti transfer hanya bisa diupload saat status PENDING.');
        }

        if ($order->payment_proof) {
            return back()->with('error', 'Bukti transfer sudah pernah diupload.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'payment_proof.required' => 'Bukti transfer wajib diupload.',
            'payment_proof.image'    => 'File harus berupa gambar.',
            'payment_proof.mimes'    => 'Format gambar: JPG, JPEG, PNG.',
            'payment_proof.max'      => 'Ukuran maksimal 2MB.',
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $order->update(['payment_proof' => $path]);

        return back()->with('success', 'Bukti transfer berhasil diupload!');
    }
}
