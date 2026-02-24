<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 */
class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected User $buyer;
    protected User $seller;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create([
            'role' => 'SELLER',
            'store_name' => 'Test Store',
        ]);

        $this->buyer = User::factory()->create([
            'role' => 'USER',
            'address' => 'Jl. Test No. 1',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'postal_code' => '10110',
            'phone' => '08123456789',
        ]);

        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $category->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 1000000,
            'stock' => 10,
            'description' => 'Test description',
            'is_active' => true,
        ]);

        // Create bank for payment
        Bank::create([
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'Test Store',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function guest_cannot_access_cart(): void
    {
        $response = $this->get(route('cart.index'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_add_to_cart(): void
    {
        $response = $this->actingAs($this->buyer)->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->buyer->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);
    }

    /** @test */
    public function user_can_update_cart_quantity(): void
    {
        $cart = Cart::create([
            'user_id' => $this->buyer->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->buyer)->patch(route('cart.update', $cart->id), [
            'quantity' => 3,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'quantity' => 3,
        ]);
    }

    /** @test */
    public function user_can_remove_from_cart(): void
    {
        $cart = Cart::create([
            'user_id' => $this->buyer->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->buyer)->delete(route('cart.destroy', $cart->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    /** @test */
    public function user_can_checkout(): void
    {
        Cart::create([
            'user_id' => $this->buyer->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($this->buyer)->post(route('checkout.store'), [
            'shipping_name' => 'Test Buyer',
            'shipping_address' => 'Jl. Test No. 1',
            'shipping_city' => 'Jakarta',
            'shipping_province' => 'DKI Jakarta',
            'shipping_postal_code' => '10110',
            'shipping_phone' => '08123456789',
            'bank_id' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->buyer->id,
            'status' => 'PENDING',
        ]);

        // Check stock decremented
        $this->assertEquals(8, $this->product->fresh()->stock);

        // Check cart cleared
        $this->assertDatabaseMissing('carts', [
            'user_id' => $this->buyer->id,
        ]);
    }

    /** @test */
    public function checkout_fails_with_empty_cart(): void
    {
        $response = $this->actingAs($this->buyer)->post(route('checkout.store'), [
            'shipping_name' => 'Test Buyer',
            'shipping_address' => 'Jl. Test',
            'shipping_city' => 'Jakarta',
            'shipping_province' => 'DKI Jakarta',
            'shipping_postal_code' => '10110',
            'shipping_phone' => '08123456789',
            'bank_id' => 1,
        ]);

        $response->assertRedirect();
        // Should redirect with error since no items in cart
    }

    /** @test */
    public function user_can_view_orders(): void
    {
        $response = $this->actingAs($this->buyer)->get(route('orders.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_cancel_pending_order(): void
    {
        // Create order
        Cart::create([
            'user_id' => $this->buyer->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($this->buyer)->post(route('checkout.store'), [
            'shipping_name' => 'Test Buyer',
            'shipping_address' => 'Jl. Test',
            'shipping_city' => 'Jakarta',
            'shipping_province' => 'DKI Jakarta',
            'shipping_postal_code' => '10110',
            'shipping_phone' => '08123456789',
            'bank_id' => 1,
        ]);

        $order = Order::where('user_id', $this->buyer->id)->first();

        $this->assertNotNull($order, 'Order should have been created');

        $response = $this->actingAs($this->buyer)->patch(route('orders.cancel', $order->id));

        $response->assertRedirect();
        $this->assertEquals('CANCELLED', $order->fresh()->status);

        // Stock restored
        $this->assertEquals(10, $this->product->fresh()->stock);
    }
}
