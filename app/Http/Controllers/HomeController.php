<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

/**
 * HomeController — Public homepage
 */
class HomeController extends Controller
{
    public function index()
    {
        // Get all active categories
        $categories = Category::active()->withCount('products')->get();

        // Get latest products
        $latestProducts = Product::active()
            ->with(['productImages', 'category'])
            ->latest()
            ->take(4)
            ->get();

        // SQL Query:
        /*
        SELECT * FROM products p
        WHERE p.status = 'active'
        INNER JOIN product_images ON p.id = product_images.product_id
        INNER JOIN categories ON p.category_id = categories.id
        ORDER BY p.created_at DESC
        LIMIT 4;
        */

        return view('pages.home', compact('categories', 'latestProducts'));
    }
}
