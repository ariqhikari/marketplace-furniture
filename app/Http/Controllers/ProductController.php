<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * ProductController — Public product browsing
 */
class ProductController extends Controller
{
    /**
     * Product listing with filter, sort, pagination
     */
    public function index(Request $request)
    {
        $query = Product::active()->with(['productImages', 'category', 'user']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // ALGORITHM: Filter by material
        if ($request->filled('material')) {
            $query->where('material', $request->material);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sort = $request->input('sort', 'newest');
        $query = match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::active()->withCount('products')->get();

        return view('pages.products.index', compact('products', 'categories'));
    }

    /**
     * Product detail page
     */
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with(['productImages', 'category', 'user'])
            ->firstOrFail();

        // Related products — same category, limit 4
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('productImages')
            ->take(4)
            ->get();

        return view('pages.products.show', compact('product', 'relatedProducts'));
    }

    /**
     */
    public function search(Request $request)
    {
        $keyword = $request->input('q', '');

        $products = Product::active()
            ->search($keyword)
            ->with(['productImages', 'category'])
            ->paginate(12)
            ->withQueryString();

        return view('pages.products.search', compact('products', 'keyword'));
    }
}
