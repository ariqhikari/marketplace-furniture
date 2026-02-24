<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

/**
 * CategoryController — Public: browse products by category
 */
class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->active()->firstOrFail();

        $products = Product::active()
            ->byCategory($category->id)
            ->with(['productImages', 'user'])
            ->latest()
            ->paginate(12);

        $categories = Category::active()->withCount('products')->get();

        return view('pages.products.index', compact('products', 'categories', 'category'));
    }
}
