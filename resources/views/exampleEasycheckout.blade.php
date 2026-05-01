<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="StyleBee Payment Gateway">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>StyleBee - SSLCommerz Checkout</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
        body { background-color: #f4f7f6; font-family: 'Inter', sans-serif; }
        .container { max-width: 1000px; }
        
        /* Header Styling */
        .stylebee-header h2 { font-weight: 800; letter-spacing: -1px; color: #1a1a1a; }
        .stylebee-header p { color: #666; }

        /* Card & Section Styling */
        .billing-section, .shopping-cart__totals {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #e1e8e7;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        /* Form Customization */
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #dce1e0;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #000;
            box-shadow: 0 0 0 0.2rem rgba(0,0,0,0.05);
        }
        label { font-weight: 600; font-size: 0.9rem; color: #444; margin-bottom: 8px; }

        /* Cart Table */
        .cart-totals th { font-weight: 500; color: #777; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
        .cart-totals td { font-weight: 700; color: #1a1a1a; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
        .total-row th, .total-row td { border-bottom: none; font-size: 1.2rem; color: #000; }

        /* The Button */
        .btn-stylebee {
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: transform 0.2s, background-color 0.2s;
        }
        .btn-stylebee:hover {
            background-color: #333;
            color: #fff;
            transform: translateY(-2px);
        }
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
        .shopping-cart__totals {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .cart-totals { width: 100%; }
        .cart-totals th { text-align: left; padding: 10px 0; }
        .cart-totals td { text-align: right; font-weight: bold; }
    </style>
</head>
<body class="bg-light">
<div class="container">
    <div class="py-5 text-center">
        <h2>StyleBee Online Payment</h2>
        <p class="lead">Complete your purchase securely via SSLCommerz.</p>
    </div>

    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <div class="shopping-cart__totals">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Cart Totals</span>
                </h4>
                <table class="cart-totals">
                    <tbody>
                        <tr>
                            <th>Subtotal</th>
                            <td>BDT {{ Cart::instance('cart')->subTotal() }}</td>
                        </tr>
                        <tr>
                            <th>Shipping</th>
                            <td>Free</td>
                        </tr>
                        <tr>
                            <th>VAT</th>
                            <td>BDT {{ Cart::instance('cart')->tax() }}</td>
                        </tr>
                        <tr class="border-top">
                            <th class="pt-2">Total</th>
                            <td class="pt-2 text-primary">BDT {{ Cart::instance('cart')->total() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Billing Address</h4>
            <form method="POST" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="customer_name">Full Name</label>
                        <input type="text" class="form-control" id="customer_name" placeholder="Enter your name" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="mobile">Mobile</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">+88</span>
                        </div>
                        <input type="text" class="form-control" id="mobile" placeholder="017XXXXXXXX" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email">Email <span class="text-muted">(Optional)</span></label>
                    <input type="email" class="form-control" id="email" placeholder="you@example.com">
                </div>

                <div class="mb-3">
                    <label for="address">Shipping Address</label>
                    <input type="text" class="form-control" id="address" placeholder="Rajshahi 6200, Bangladesh" required>
                </div>

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="country">Country</label>
                        <select class="custom-select d-block w-100" id="country" required>
                            <option value="Bangladesh">Bangladesh</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="state">State</label>
                        <select class="custom-select d-block w-100" id="state" required>
                            <option value="Rajshahi">Rajshahi</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="zip">Zip</label>
                        <input type="text" class="form-control" id="zip" placeholder="6200" required>
                    </div>
                </div>
                
                <hr class="mb-4">
                
                <!-- DYNAMIC TOTAL AMOUNT FROM CART -->
                <input type="hidden" value="{{ Cart::instance('cart')->total() }}" name="amount" id="total_amount" required/>

                <button class="btn btn-primary btn-lg btn-block" id="manualPayBtn">
                    Pay Now
                </button>
            </form>
        </div>
    </div>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">&copy; 2026 StyleBee</p>
    </footer>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

{{-- <script>
    $('#pay-button').click(function(e) {
        e.preventDefault();

        // 1. Collect dynamic data from your form inputs
        var customerData = {
            cus_name: $('#name').val(),   // Get value from Name input
            cus_phone: $('#phone').val(), // Get value from Phone input
            cus_email: $('#email').val(), // Get value from Email input
            cus_addr1: $('#address').val(),
            amount: $('#total_amount').val() // The dynamic cart total
        };

        // 2. The AJAX call
        $.ajax({
            url: "{{ url('/pay-via-ajax') }}",
            type: 'POST',
            data: customerData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // If SSLCommerz returns the redirect URL
                if (response) {
                    window.location.href = response;
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert("Check console for the communication error details.");
            }
        });
    });
</script> --}}

<script>
    $('#manualPayBtn').click(function() {
        var customerData = {
            // These IDs must match your input field IDs exactly
            cus_name: $('#name').val(),
            cus_phone: $('#phone').val(),
            cus_email: $('#email').val(),
            cus_addr1: $('#address').val(),
            amount: $('#total_amount').val(),
            _token: "{{ csrf_token() }}" // Explicitly pass the token in the data
        };

        $.ajax({
            url: "{{ route('payViaAjax') }}",
            type: 'POST',
            data: customerData,
            success: function(response) {
                // SSLCommerz returns a JSON object with a 'data' field containing the URL
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    window.location.href = result.data;
                } else {
                    alert("Gateway Error: " + result.message);
                }
            },
            error: function(xhr) {
                // If it still fails, check the console for the 419 error
                console.log(xhr.responseText);
                if(xhr.status == 419) {
                    alert("CSRF Token mismatch. Try refreshing the page.");
                }
            }
        });
    });
</script>
</body>
</html>