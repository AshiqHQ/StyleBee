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
        $f_brands = $request->query('brands');
        $f_categories = $request->query('categories');
        $min_price = $request->query('min')? $request->query('min'):500;
        $max_price = $request->query('max')? $request->query('max'):10000;

        $products = Product::where(function($query) use ($f_brands) {
                $query->whereIn('brand_id', explode(',', $f_brands))->orWhereRaw("'".$f_brands."' = ''");
        })->
        where(function($query) use ($f_categories) {
            $query->whereIn('category_id', explode(',', $f_categories))->orWhereRaw("'".$f_categories."' = ''");
        })->
        where(function($query) use ($min_price, $max_price) {
            $query->whereBetween('regular_price', [$min_price, $max_price])->
            orWhereBetween('sale_price', [$min_price, $max_price]);
        })->
        orderBy('created_at', 'desc')->paginate(12);

        $brands = Brand::orderBy('name', 'ASC')->withCount('products')->get();
        $categories = Category::orderBy('name', 'ASC')->withCount('products')->get();


        return view('shop', compact('products', 'brands', 'f_brands', 'categories', 'f_categories', 'min_price', 'max_price'));   
    }

    public function product_details($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $rproducts = Product::where('slug', '<>', $product_slug)->get()->take(8);
        return view('details', compact('product', 'rproducts'));


    }

    public function storeReview(Request $request)
    {
        // $request->validate([
        //     'product_id' => 'required',
        //     'rating'     => 'required|integer',
        //     'name'       => 'required',
        //     'email'      => 'required|email',
        //     'comment'    => 'required',
        // ]);

        // Review::create($request->all());

        // return back()->with('success', 'Review submitted!');
        dd($request->all());
    }


}
