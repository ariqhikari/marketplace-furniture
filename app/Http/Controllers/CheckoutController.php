<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cart;
use App\Http\Requests\CheckoutRequest;
use App\Services\OrderService;

/**
 * CheckoutController — Order creation
 */
class CheckoutController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index()
    {
        $carts = Cart::with(['product.productImages', 'product.user'])
            ->where('user_id', auth()->id())
            ->whereHas('product')
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Keranjang Anda kosong.');
        }

        $grandTotal = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

        $user = auth()->user();
        $banks = Bank::active()->get();

        return view('pages.checkout', compact('carts', 'grandTotal', 'user', 'banks'));
    }

    /**
     */
    public function store(CheckoutRequest $request)
    {
        try {
            $order = $this->orderService->createOrder(
                auth()->id(),
                $request->validated()
            );

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order berhasil dibuat! Nomor order: ' . $order->order_number);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
