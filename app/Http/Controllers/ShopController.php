<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Get filter inputs
        $f_brands = $request->query('brands');
        $f_categories = $request->query('categories');
        $min_price = $request->query('min', 500);
        $max_price = $request->query('max', 10000);

        $query = Product::query();

        // 1. Filter by Brands (if any selected)
        $query->when($f_brands, function ($q) use ($f_brands) {
            $q->whereIn('brand_id', explode(',', $f_brands));
        });

        // 2. Filter by Categories (if any selected)
        $query->when($f_categories, function ($q) use ($f_categories) {
            $q->whereIn('category_id', explode(',', $f_categories));
        });

        // 3. Filter by Price Range (Check both Regular and Sale Price)
        $query->where(function($q) use ($min_price, $max_price) {
            $q->whereBetween('regular_price', [$min_price, $max_price])
              ->orWhereBetween('sale_price', [$min_price, $max_price]);
        });

        // Get results
        $products = $query->orderBy('created_at', 'desc')->paginate(12);

        // Sidebar data
        $brands = Brand::orderBy('name', 'ASC')->get();
        $categories = Category::orderBy('name', 'ASC')->get();

        return view('shop', compact('products', 'brands', 'f_brands', 'categories', 'f_categories', 'min_price', 'max_price'));   
    }

    public function product_details($product_slug)
    {
        $product = Product::where('slug', $product_slug)->firstOrFail();
        $rproducts = Product::where('slug', '<>', $product_slug)->take(8)->get();
        return view('details', compact('product', 'rproducts'));
    }

    public function storeReview(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|max:1000',
        ]);

        Review::create($validated);

        return back()->with('success', 'Review submitted successfully!');
    }
}