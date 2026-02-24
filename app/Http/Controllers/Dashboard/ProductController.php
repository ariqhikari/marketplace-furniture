<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

/**
 * Seller Product CRUD
 */
class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $query = Product::where('user_id', auth()->id())->with(['category', 'productImages']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sort = $request->input('sort', 'created_at');
        $dir  = $request->input('dir', 'desc');
        $query->orderBy($sort, $dir);

        $products = $query->paginate(10)->withQueryString();

        return view('dashboard.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('dashboard.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $images = $request->file('images', []);

        try {
            $product = $this->productService->store($data, $images);
            \Log::info('Produk baru dibuat', [
                'id'   => $product->id ?? null,
                'user' => auth()->id(),
            ]);
            return redirect()->route('dashboard.products.index')
                ->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Log::error('Gagal tambah produk', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Gagal menyimpan produk.');
        }
    }

    public function edit(int $id)
    {
        $product = Product::with('productImages')->findOrFail($id);

        // Authorization: only own products
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::active()->get();

        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, int $id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id !== auth()->id()) {
            abort(403);
        }

        $data   = $request->validated();
        $images = $request->file('images', []);
        $deleteImageIds = $request->input('delete_images', []);

        $this->productService->update($product, $data, $images, $deleteImageIds);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id !== auth()->id()) {
            abort(403);
        }

        if ($product->orderItems()->exists()) {
            return back()->with('error', 'Produk tidak bisa dihapus karena sudah memiliki pesanan.');
        }

        $this->productService->delete($product);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
