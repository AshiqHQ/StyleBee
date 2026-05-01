@extends('layouts.app')

@section('content')

<script>
    (function (window, document) {
        var loader = function () {
            var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
            script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7);
            tag.parentNode.insertBefore(script, tag);
        };

        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document);
</script>

<main class="pt-90">
<div class="mb-4 pb-4"></div>
<section class="shop-checkout container">
    <h2 class="page-title">Shipping and Checkout</h2>
    <div class="checkout-steps">
    <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
        <span class="checkout-steps__item-number">01</span>
        <span class="checkout-steps__item-title">
        <span>Shopping Bag</span>
        <em>Manage Your Items List</em>
        </span>
    </a>
    <a href="javascript:void(0)" class="checkout-steps__item active">
        <span class="checkout-steps__item-number">02</span>
        <span class="checkout-steps__item-title">
        <span>Shipping and Checkout</span>
        <em>Checkout Your Items List</em>
        </span>
    </a>
    <a href="javascript:void(0)" class="checkout-steps__item">
        <span class="checkout-steps__item-number">03</span>
        <span class="checkout-steps__item-title">
        <span>Confirmation</span>
        <em>Review And Submit Your Order</em>
        </span>
    </a>
    </div>
    <form name="checkout-form" action="{{ route('cart.place.an.order') }}" method="POST">
    @csrf
    <div class="checkout-form">
        <div class="billing-info__wrapper">
        <div class="row">
            <div class="col-6">
            <h4>SHIPPING DETAILS</h4>
            </div>
            <div class="col-6">
            </div>
        </div>

        @if($address)
        <div class="row">
            <div class="col-md-12">
                <div class="my-account__address-list">
                    <div class="my-account__address-list-item">
                        <div class="my-account__address-item__detail">
                            <p>{{ $address->name }}</p>
                            <p>{{ $address->address }}</p>
                            <p>{{ $address->landmark }}</p>
                            <p>{{ $address->city }}</p>
                            <p>{{ $address->state }}</p>
                            <p>{{ $address->country }}</p>
                            <p>{{ $address->zip }}</p>
                            <br/>                        
                            <p>{{ $address->phone }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @else
        <div class="row mt-5">
            <div class="col-md-6">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="name" required="" value="{{ old('name') }}">
                <label for="name">Full Name *</label>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="phone" required="" value="{{ old('phone') }}">
                <label for="phone">Phone Number *</label>
                @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            </div>
            <div class="col-md-4">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="zip" required="" value="{{ old('zip') }}">
                <label for="zip">Pincode *</label>
                @error('zip')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            </div>
            <div class="col-md-4">
            <div class="form-floating mt-3 mb-3">
                <input type="text" class="form-control" name="state" required="" value="{{ old('state') }}">
                <label for="state">State *</label>
                @error('state')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            </div>
            <div class="col-md-4">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="city" required="" value="{{ old('city') }}">
                <label for="city">Town / City *</label>
                @error('city')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="address" required="" value="{{ old('address') }}">
                <label for="address">House no, Building Name *</label>
                @error('address')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="locality" required="" value="{{ old('locality') }}">
                <label for="locality">Road Name, Area, Colony *</label>
                @error('locality')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            </div>
            <div class="col-md-12">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="landmark" required="" value="{{ old('landmark') }}">
                <label for="landmark">Landmark *</label>
                @error('landmark')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            </div>
        </div>
        @endif
        </div>
        <div class="checkout__totals-wrapper">
        <div class="sticky-content">
            <div class="checkout__totals">
            <h3>Your Order</h3>
            <table class="checkout-cart-items">
                <thead>
                <tr>
                    <th>PRODUCT</th>
                    <th align="right">SUBTOTAL</th>
                </tr>
                </thead>
                <tbody>

                @foreach (Cart::instance('cart') as $item)
                <tr>
                    <td>
                    {{ $item->name }} x {{ $item->qty }}
                    </td>
                    <td align="right">
                    {{ $item->subtotal }}
                    </td>
                </tr>
                @endforeach

                </tbody>                
            </table>
            
            {{-- --------------------discount code should place here (V38, T19:30)--------------------- --}}


            <table class="checkout-totals">
                <tbody>
                <tr>
                    <th>SUBTOTAL</th>
                    <td class="text-right">BDT: {{ Cart::instance('cart')->subtotal() }}</td>
                </tr>
                <tr>
                    <th>SHIPPING</th>
                    <td class="text-right">Free shipping</td>
                </tr>
                <tr>
                    <th>VAT</th>
                    <td class="text-right">BDT: {{ Cart::instance('cart')->tax() }}</td>
                </tr>
                <tr>
                    <th>TOTAL</th>
                    <td class="text-right">BDT: {{ Cart::instance('cart')->total() }}</td>
                </tr>
                </tbody>
            </table>
            </div>
            <div class="checkout__payment-methods">
            <h3>Payment Methods</h3>
            <div class="form-check">
                <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                id="mode2" value="cod" checked>
                <label class="form-check-label" for="mode2">
                Cash on delivery
                <p class="option-detail">
                    Pay with cash upon delivery.
                </p>
                </label>
            </div>
            <div class="policy-text">
                Your personal data will be used to process your order, support your experience throughout this
                website, and for other purposes described in our <a href="terms.html" target="_blank">privacy
                policy</a>.
            </div>
            </div>
            <button class="btn btn-primary btn-checkout">PLACE ORDER</button>
        </div>
        </div>
    </div>
    </form>
</section>
</main>

@endsection