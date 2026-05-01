<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        // Add the product to the cart
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }




    public function checkout()
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }

        $address = Address::where('user_id', Auth::user()->id)->where('isdefault', 1)->first();
        return view('checkout', compact('address'));
    }

    public function place_an_order(Request $request)
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id', $user_id)->where('isdefault', 1)->first();

        if(!$address){

            $request->validate([
                'name' => 'required|max:255',
                'phone' => 'required|numeric|digits:11',
                'zip' => 'required|numeric|digits:4',
                'state' => 'required|max:100',
                'city' => 'required|max:100',
                'address' => 'required|max:255',
                'locality' => 'required|max:255',
                'landmark' => 'required|max:255',
            ]);

            $address = new Address();
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->zip = $request->zip;
            $address->state = $request->state;
            $address->city = $request->city;
            $address->address = $request->address;
            $address->locality = $request->locality;
            $address->landmark = $request->landmark;
            $address->country = 'Bangladesh';
            $address->user_id = $user_id;
            $address->isdefault = true;
            $address->save();
            
        }

        $this->setAmountForCheckout();

        $order = new Order();
        $order->user_id = $user_id;
        $order->subtotal = Session::get('checkout')['subtotal'];

        // discount code will be here V39 T15:50

        $order->tax = Session::get('checkout')['tax'];
        $order->total = Session::get('checkout')['total'];
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->locality = $address->locality;
        $order->address = $address->address;
        $order->city = $address->city;
        $order->state = $address->state;
        $order->country = $address->country;
        $order->landmark = $address->landmark;
        $order->zip = $address->zip;
        $order->save();

        foreach(Cart::instance('cart')->content() as $item){
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->quantity = $item->qty;
            $orderItem->price = $item->price;
            $orderItem->save();
        }

        if($request->mode == 'cod'){
            $transaction = new Transaction();
            $transaction->user_id = $order->user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = "pending";
            $transaction->save();   
        }

        elseif($request->mode == 'online'){
            // online payment code will be here  
        }

        Cart::instance('cart')->destroy();
        Session::forget('checkout');

        // coupon and discounts code will be here V39 T21:53

        Session::put('order_id', $order->id);
        return redirect()->route('cart.order.confirmation');

    }

    public function setAmountForCheckout()
    {
        if(!Cart::instance('cart')->content()->count() > 0){
            Session::forget('checkout');
            return;
        }


        // coupn and discounts code will be here V39 T10:30


        else{
            // Remove commas and convert to float
            $subtotal = str_replace(',', '', Cart::instance('cart')->subTotal());
            $tax = str_replace(',', '', Cart::instance('cart')->tax());
            $total = str_replace(',', '', Cart::instance('cart')->total());
            
            Session::put('checkout', [
                'subtotal' => floatval($subtotal),
                'tax' => floatval($tax),
                'total' => floatval($total),
            ]);
        }
    }

    public function order_confirmation()
    {
        if(Session::has('order_id')){
            $order = Order::find(Session::get('order_id'));
            
            // Optional: Verify order belongs to current user
            if($order && $order->user_id == Auth::user()->id){
                return view('order-confirmation', compact('order'));
            }
        }
        
        // If no order_id in session or order not found, redirect to cart
        return redirect()->route('cart.index')->with('error', 'No order found');
    }
}
