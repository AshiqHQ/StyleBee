<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $sproducts = Product::whereNotNull('sale_price')->where('sale_price', '<>', '')->inRandomOrder()->get()->take(8);
        $fproducts = Product::where('featured', 1)->get()->take(8);
        return view('index', compact('categories', 'sproducts', 'fproducts'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        
        // Search by name or slug
        $products = Product::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('slug', 'LIKE', "%{$query}%")
                    ->take(5) // Limit results for the dropdown
                    ->get();

        return response()->json($products);
    }
}


    