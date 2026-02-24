<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 */
class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $seller;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seller = User::factory()->create([
            'role' => 'SELLER',
            'store_name' => 'Test Store',
        ]);
        $this->category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function guest_can_view_products_page(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_can_view_product_detail(): void
    {
        $product = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 1000000,
            'stock' => 10,
            'description' => 'Test description',
            'is_active' => true,
        ]);

        $response = $this->get(route('products.show', $product->slug));
        $response->assertStatus(200);
        $response->assertSee('Test Product');
    }

    /** @test */
    public function seller_can_create_product(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->seller)->post(route('dashboard.products.store'), [
            'name' => 'New Product',
            'category_id' => $this->category->id,
            'price' => 2000000,
            'stock' => 15,
            'description' => 'Deskripsi produk baru yang sangat lengkap dan detail untuk furniture berkualitas tinggi',
            'material' => 'Kayu Jati',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('dashboard.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'New Product']);
    }

    /** @test */
    public function seller_can_update_product(): void
    {
        $product = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'name' => 'Old Product',
            'slug' => 'old-product',
            'price' => 1000000,
            'stock' => 10,
            'description' => 'Old description',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->seller)->put(route('dashboard.products.update', $product->id), [
            'name' => 'Updated Product',
            'category_id' => $this->category->id,
            'price' => 1500000,
            'stock' => 20,
            'description' => 'Deskripsi produk yang sudah diperbarui dengan detail lengkap untuk furniture terbaru di toko kami',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('dashboard.products.index'));
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Product']);
    }

    /** @test */
    public function seller_can_delete_product(): void
    {
        $product = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'name' => 'Delete Me',
            'slug' => 'delete-me',
            'price' => 1000000,
            'stock' => 10,
            'description' => 'Will be deleted',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->seller)->delete(route('dashboard.products.destroy', $product->id));

        $response->assertRedirect(route('dashboard.products.index'));
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /** @test */
    public function products_can_be_searched(): void
    {
        Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'name' => 'Sofa Unik Special',
            'slug' => 'sofa-unik-special',
            'price' => 1000000,
            'stock' => 10,
            'description' => 'Searchable product',
            'is_active' => true,
        ]);

        $response = $this->get(route('products.search', ['keyword' => 'Sofa Unik']));
        $response->assertStatus(200);
        $response->assertSee('Sofa Unik Special');
    }

    /** @test */
    public function user_cannot_access_seller_dashboard_products(): void
    {
        $user = User::factory()->create(['role' => 'USER']);
        $response = $this->actingAs($user)->get(route('dashboard.products.index'));
        $response->assertStatus(403);
    }
}
