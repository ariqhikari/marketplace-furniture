<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * OrderService — Business logic for orders
 */
class OrderService
{
    /**
     * Create order from cart items
     */
    public function createOrder(int $userId, array $shippingData): Order
    {
        return DB::transaction(function () use ($userId, $shippingData) {
            $carts = Cart::with('product')->where('user_id', $userId)->whereHas('product')->get();

            if ($carts->isEmpty()) {
                throw new \Exception('Keranjang belanja kosong.');
            }

            $grandTotal = 0;
            foreach ($carts as $cart) {
                if (!$cart->product->isAvailable($cart->quantity)) {
                    throw new \Exception("Stok {$cart->product->name} tidak mencukupi.");
                }
                $grandTotal += $cart->product->price * $cart->quantity;
            }

            $orderNumber = '';
            do {
                $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            } while (Order::where('order_number', $orderNumber)->exists());

            $order = Order::create([
                'order_number'       => $orderNumber,
                'user_id'            => $userId,
                'total_price'        => $grandTotal,
                'status'             => 'PENDING',
                'shipping_name'      => $shippingData['shipping_name'],
                'shipping_phone'     => $shippingData['shipping_phone'],
                'shipping_address'   => $shippingData['shipping_address'],
                'shipping_city'      => $shippingData['shipping_city'],
                'shipping_province'  => $shippingData['shipping_province'],
                'shipping_postal_code' => $shippingData['shipping_postal_code'],
                'bank_id'            => $shippingData['bank_id'],
                'notes'              => $shippingData['notes'] ?? null,
            ]);

            foreach ($carts as $cart) {
                $subtotal = $cart->product->price * $cart->quantity;

                $order->orderItems()->create([
                    'product_id' => $cart->product_id,
                    'seller_id'  => $cart->product->user_id,
                    'quantity'   => $cart->quantity,
                    'price'      => $cart->product->price,
                    'subtotal'   => $subtotal,
                    'status'     => 'PENDING',
                ]);

                $cart->product->decrement('stock', $cart->quantity);
            }

            // Clear cart
            Cart::where('user_id', $userId)->delete();

            return $order;
        });
    }

    /**
     * Cancel order and restore stock
     */
    public function cancelOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->orderItems as $item) {
                if ($item->status !== 'CANCELLED') {
                    $item->product->increment('stock', $item->quantity);
                    $item->update(['status' => 'CANCELLED']);
                }
            }

            $order->update(['status' => 'CANCELLED']);
        });
    }
}
