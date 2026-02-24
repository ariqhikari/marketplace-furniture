<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * CartController — Shopping cart
 */
class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with(['product.productImages'])
            ->where('user_id', auth()->id())
            ->whereHas('product')
            ->get();

        $total = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

        return view('pages.cart', compact('carts', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->input('quantity', 1);

        // Validate stock availability
        if (!$product->isAvailable($quantity)) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // Check if already in cart → update quantity
        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            $newQty = $cart->quantity + $quantity;
            if (!$product->isAvailable($newQty)) {
                return back()->with('error', 'Stok produk tidak mencukupi.');
            }
            $cart->update(['quantity' => $newQty]);
        } else {
            Cart::create([
                'user_id'    => auth()->id(),
                'product_id' => $product->id,
                'quantity'   => $quantity,
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Cart $cart, Request $request)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['quantity' => 'required|integer|min:1']);

        if (!$cart->product->isAvailable($request->quantity)) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
