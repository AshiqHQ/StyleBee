<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Intervention\Image\Laravel\Facades\Image;
class AdminController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'DESC')->get()->take(10);
        $dashboardDatas = DB::select("Select sum(total) As TotalAmount,
            sum(if(status='ordered',total,0)) As TotalOrderedAmount,
            sum(if(status='delivered',total,0)) As TotalDeliveredAmount,
            sum(if(status='canceled',total,0)) As TotalCanceledAmount,
            Count(*) As Total,
            sum(if(status='ordered',1,0)) As TotalOrdered,
            sum(if(status='delivered',1,0)) As TotalDelivered,
            sum(if(status='canceled',1,0)) As TotalCanceled
            From Orders");
        
        $monthlyDatas = DB::select("SELECT M.id As MonthNo, M.name As MonthName,
            IFNULL(D.TotalAmount,0) As TotalAmount,
            IFNULL(D.TotalOrderedAmount,0) As TotalOrderedAmount,
            IFNULL(D.TotalDeliveredAmount,0) As TotalDeliveredAmount,
            IFNULL(D.TotalCanceledAmount,0) As TotalCanceledAmount FROM month_names M
            LEFT JOIN (Select DATE_FORMAT(created_at, '%b') As MonthName,
            MONTH(created_at) As MonthNo,
            sum(total) As TotalAmount,
            sum(if(status='ordered',total,0)) As TotalOrderedAmount,
            sum(if(status='delivered',total,0)) As TotalDeliveredAmount,
            sum(if(status='canceled',total,0)) As TotalCanceledAmount
            From Orders WHERE YEAR(created_at)=YEAR(NOW()) GROUP BY YEAR(created_at), MONTH(created_at) , DATE_FORMAT(created_at, '%b')
            Order By MONTH(created_at)) D On D.MonthNo=M.id");

        $AmountM = implode(',', collect($monthlyDatas)->pluck('TotalAmount')->toArray());
        $OrderedAmountM = implode(',', collect($monthlyDatas)->pluck('TotalOrderedAmount')->toArray());
        $DeliveredAmountM = implode(',', collect($monthlyDatas)->pluck('TotalDeliveredAmount')->toArray());
        $CanceledAmountM = implode(',', collect($monthlyDatas)->pluck('TotalCanceledAmount')->toArray());

        $TotalAmount = collect($monthlyDatas)->sum('TotalAmount');
        $TotalOrderedAmount = collect($monthlyDatas)->sum('TotalOrderedAmount');
        $TotalDeliveredAmount = collect($monthlyDatas)->sum('TotalDeliveredAmount');
        $TotalCanceledAmount = collect($monthlyDatas)->sum('TotalCanceledAmount');

        return view('admin.index', compact('orders', 'dashboardDatas', 'monthlyDatas', 'AmountM', 'OrderedAmountM', 'DeliveredAmountM', 'CanceledAmountM', 'TotalAmount', 'TotalOrderedAmount', 'TotalDeliveredAmount', 'TotalCanceledAmount'));
    }

    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }
    public function brand_add()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request)
    {
        // 1. Ensure image is required to avoid 'null' errors
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if($request->hasFile('image')) {
            $image = $request->file('image');
            // 2. Use the file object extension directly
            $file_extension = $image->extension(); 
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            
            // 3. Generate the thumbnail
            $this->GenerateBrandThumbnailsImage($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return redirect()->route(route: 'admin.brands')->with('status', 'Brand added successfully.');
    }

    public function brand_edit($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function brand_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if($request->hasFile('image')) {
            if(File::exists(public_path('uploads/brands/' . $brand->image))){
                File::delete(public_path('uploads/brands/' . $brand->image));
            }
            $image = $request->file('image');
            // 2. Use the file object extension directly
            $file_extension = $image->extension(); 
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            
            // 3. Generate the thumbnail
            $this->GenerateBrandThumbnailsImage($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return redirect()->route(route: 'admin.brands')->with('status', 'Brand updated successfully.');
    }

    public function GenerateBrandThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');
        
        // 4. Ensure directory exists to prevent 'folder not found' errors
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // 5. Use getRealPath() for the Intervention library
        $img = Image::read($image->getRealPath()); 
        $img->cover(124, 124, "top");
        $img->save($destinationPath . '/' . $imageName);
    }

    public function brand_delete($id)
    {
        $brand = Brand::find($id);
        if(File::exists(public_path('uploads/brands/' . $brand->image))){
            File::delete(public_path('uploads/brands/' . $brand->image));
        }
        $brand->delete();
        return redirect()->route(route: 'admin.brands')->with('status', 'Brand deleted successfully.');
    }



    public function categories()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function category_add()
    {
        return view('admin.category-add');
    }

    public function category_store(Request $request)
    {
        // Validation logic for category can be added here
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if($request->hasFile('image')) {
            $image = $request->file('image');
            // 2. Use the file object extension directly
            $file_extension = $image->extension(); 
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            
            // 3. Generate the thumbnail
            $this->GenerateCategoryThumbnailsImage($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        return redirect()->route(route: 'admin.categories')->with('status', 'Category added successfully.');
    }

    public function GenerateCategoryThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');
        
        // 4. Ensure directory exists to prevent 'folder not found' errors
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // 5. Use getRealPath() for the Intervention library
        $img = Image::read($image->getRealPath()); 
        $img->cover(124, 124, "top");
        $img->save($destinationPath . '/' . $imageName);
    }

    public function category_edit($id)
    {
        $category = Category::find($id);
        return view('admin.category-edit', compact('category'));
    }

    public function category_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if($request->hasFile('image')) {
            if(File::exists(public_path('uploads/categories/' . $category->image))){
                File::delete(public_path('uploads/categories/' . $category->image));
            }
            $image = $request->file('image');
            // 2. Use the file object extension directly
            $file_extension = $image->extension(); 
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            
            // 3. Generate the thumbnail
            $this->GenerateCategoryThumbnailsImage($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        return redirect()->route(route: 'admin.categories')->with('status', 'Category updated successfully.');
    }

    public function category_delete($id)
    {
        $category = Category::find($id);
        if(File::exists(public_path('uploads/categories/' . $category->image))){
            File::delete(public_path('uploads/categories/' . $category->image));
        }
        $category->delete();
        return redirect()->route(route: 'admin.categories')->with('status', 'Category deleted successfully.');
    }

    public function products()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function product_add()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required|integer',
            'image' => 'required|mimes:png,jpg,jpeg|max:5120',
            'images.*' => 'mimes:png,jpg,jpeg|max:5120', // Validate each gallery image
            'category_id' => 'required',
            'brand_id' => 'required',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $request->slug; // Use the slug from the request (generated by JS)
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        // Handle Main Image
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Carbon::now()->timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailsImage($image, $imageName);
            $product->image = $imageName;
        }

        // Handle Gallery Images
        $gallery_arr = [];
        if($request->hasFile('images')) {
            foreach($request->file('images') as $key => $file) {
                // Generate unique name using timestamp + index to prevent overwriting
                $gfileName = Carbon::now()->timestamp . '-' . ($key + 1) . '.' . $file->extension();
                $this->GenerateProductThumbnailsImage($file, $gfileName);
                $gallery_arr[] = $gfileName;
            }
            $product->images = implode(',', $gallery_arr);
        }

        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product added successfully.');
    }

    public function GenerateProductThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/products');
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');
        
        // Ensure directory exists to prevent 'folder not found' errors
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        if (!file_exists($destinationPathThumbnail)) {
            mkdir($destinationPathThumbnail, 0755, true);
        }

        // Use getRealPath() for the Intervention library
        $img = Image::read($image->getRealPath()); 

        $img->cover(1200, 1200, "top");
        $img->save($destinationPath . '/' . $imageName);

        $img->cover(1200, 1200, "top");
        $img->save($destinationPathThumbnail . '/' . $imageName);
    }

    public function product_edit($id)
    {
        $product = Product::find($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function product_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,' . $request->id,
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required|integer',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:5120',
            'images.*' => 'mimes:png,jpg,jpeg|max:5120', // Validate each gallery image
            'category_id' => 'required',
            'brand_id' => 'required',
        ]);

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name); // Generate slug from the name
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_time = Carbon::now()->timestamp;

        // Handle Main Image
        if($request->hasFile('image')) {
            if(File::exists(public_path('uploads/products') . '/' . $product->image)){
                File::delete(public_path('uploads/products') . '/' . $product->image);
            }

            if(File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)){
                File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
            }
            $image = $request->file('image');
            $imageName = Carbon::now()->timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailsImage($image, $imageName);
            $product->image = $imageName;
        }

        // Handle Gallery Images
        $gallery_arr = [];
        if($request->hasFile('images')) {
            foreach(explode(',', $product->images) as $ofile) {
                if(File::exists(public_path('uploads/products') . '/' . $ofile)){
                    File::delete(public_path('uploads/products') . '/' . $ofile);
                }

                if(File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)){
                    File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
                }
            }
            foreach($request->file('images') as $key => $file) {
                // Generate unique name using timestamp + index to prevent overwriting
                $gfileName = Carbon::now()->timestamp . '-' . ($key + 1) . '.' . $file->extension();
                $this->GenerateProductThumbnailsImage($file, $gfileName);
                $gallery_arr[] = $gfileName;
            }
            $product->images = implode(',', $gallery_arr);
        }

        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product updated successfully.');
    }

    public function product_delete($id)
    {
        $product = Product::find($id);
        if(File::exists(public_path('uploads/products') . '/' . $product->image)){
            File::delete(public_path('uploads/products') . '/' . $product->image);
        }

        if(File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)){
            File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
        }

        foreach(explode(',', $product->images) as $ofile) {
            if(File::exists(public_path('uploads/products') . '/' . $ofile)){
                File::delete(public_path('uploads/products') . '/' . $ofile);
            }

            if(File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)){
                File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
            }
        }
        
        $product->delete();
        return redirect()->route(route: 'admin.products')->with('status', 'Product deleted successfully.');
    }

    public function orders()
    {
        $orders = Order::orderBy('created_at', 'DESC')->paginate(12);
        return view('admin.orders', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Order::find($order_id);
        $orderItems =  OrderItem::where('order_id', $order_id)->orderBy('id', 'ASC')->paginate(12);
        $transaction = Transaction::where('order_id', $order_id)->first(); 
        return view('admin.order-details', compact('order', 'orderItems', 'transaction'));
    }

    public function update_order_status(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = $request->order_status;
        if ($request->order_status == 'delivered')
        {
            $order->delivered_date = Carbon::now();
        }
        else if ($request->order_status == 'canceled')
        {
            $order->canceled_date = Carbon::now();
        }

        $order->save();

        if ($request->order_status == 'delivered')
        {
            $transaction = Transaction::where('order_id', $request->order_id)->first();
            $transaction->status = 'approved';
            $transaction->save();
        }

        return back()->with('status', 'Order status updated successfully.');

    }

}