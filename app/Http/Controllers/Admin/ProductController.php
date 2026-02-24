<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * Admin Product Management (all sellers)
 */
class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $query = Product::with(['category', 'user']);

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($category = $request->input('category')) {
            $query->where('category_id', $category);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::active()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function edit(int $id)
    {
        $product = Product::with('productImages')->findOrFail($id);
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, int $id)
    {
        $product = Product::findOrFail($id);
        $data    = $request->validated();
        $images  = $request->file('images', []);
        $deleteImageIds = $request->input('delete_images', []);

        $this->productService->update($product, $data, $images, $deleteImageIds);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);

        if ($product->orderItems()->exists()) {
            return back()->with('error', 'Produk tidak bisa dihapus karena sudah memiliki pesanan.');
        }

        $this->productService->delete($product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
