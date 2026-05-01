@extends('layouts.app')

@section('content')

<style>
    .my-account.container {
        margin-top: 50px;
        margin-bottom: 50px;
    }

    body {
        background-color: #f2f7fb !important;
    }

    .my-account .wg-box {
        display: block; /* Overriding potential flex conflicts */
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        position: relative;
    }

    .wg-box h5 {
        font-weight: 700;
        color: #1a202c;
        margin: 0;
    }

    /* 3. Table Styling - Exact match for the 1st image */
    .table {
        width: 100%;
        margin-top: 20px;
        border: 1px solid #e2e8f0 !important;
        border-collapse: collapse !important;
    }

    .table th {
        background-color: #f7f9fc !important;
        color: #4a5568;
        border: 1px solid #e2e8f0 !important;
        padding: 12px 15px !important;
        text-align: left;
    }

    .table td {
        background-color: #fff !important;
        color: #2d3748;
        border: 1px solid #e2e8f0 !important;
        padding: 12px 15px !important;
        vertical-align: middle;
    }

    /* 4. Navigation Sidebar Tweak */
    .my-account .col-lg-2 {
        border-right: 1px solid #e2e8f0;
        padding-right: 20px;
    }

    .page-title {
        font-weight: 800;
        font-size: 28px;
        margin-bottom: 30px;
        text-transform: uppercase;
    }

    /* 5. Utility & Button */
    .tf-button.style-1 {
        padding: 8px 25px;
        border-radius: 8px;
        border: 1.5px solid #3b82f6;
        color: #3b82f6;
        font-weight: 700;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
    }

    .tf-button.style-1:hover {
        background-color: #3b82f6;
        color: #fff;
    }

    .flex { display: flex; }
    .items-center { align-items: center; }
    .justify-between { justify-content: space-between; }
    .gap10 { gap: 10px; }

    .pname .image {
        width: 60px;
        height: 60px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #edf2f7;
    }
    
    .pname .image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .table-responsive {
        overflow-x: auto;
    }


    /* Force the text to be visible */
    .table th {
        background-color: #f7f9fc !important;
        color: #2d3748 !important; /* Dark Slate Grey - Highly Visible */
        border: 1px solid #e2e8f0 !important;
        padding: 12px 15px !important;
        text-align: left;
    }

    .table td {
        background-color: #fff !important;
        color: #1a202c !important; /* Near Black - Highly Visible */
        border: 1px solid #e2e8f0 !important;
        padding: 12px 15px !important;
        vertical-align: middle;
    }

    /* Additional fix for links if they are invisible */
    .table a {
        color: #3182ce !important;
        text-decoration: none;
    }
</style>

<main class="pt-90" style="padding-top: 0px;">
<div class="mb-4 pb-4"></div>
<section class="my-account container">
    <h2 class="page-title">Orders Details</h2>
    <div class="row">
        <div class="col-lg-2">
            @include('user.account-nav')
        </div>

        <div class="col-lg-10">
            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <h5>Ordered Items</h5>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.orders') }}">Back</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-striped table-transaction">
                        <tr>
                            <th>Order No</th>
                            <td>{{ $order->id }}</td>

                            <th>Mobile</th>
                            <td>{{ $order->phone }}</td>

                            <th>Zip Code</th>
                            <td>{{ $order->zip }}</td>
                        </tr>

                        <tr>
                            <th>Order Date</th>
                            <td>{{ $order->created_at }}</td>

                            <th>Delivered Date</th>
                            <td>{{ $order->delivered_date }}</td>

                            <th>Canceled Date</th>
                            <td>{{ $order->canceled_date }}</td>
                        </tr>

                        <tr>
                            <th>Order Status</th>
                            <td colspan="5">
                                @if($order->status == 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                @elseif($order->status == 'canceled')
                                    <span class="badge bg-danger">Canceled</span>
                                @else
                                    <span class="badge bg-warning">Ordered</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <h5>Ordered Items</h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">SKU</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Brand</th>
                                <th class="text-center">Options</th>
                                <th class="text-center">Return Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderItems as $item)
                            <tr>                            
                                <td class="pname">
                                    <div class="image">
                                        <img src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image }}" alt="{{ $item->product->name }}" class="image">
                                    </div>
                                    <div class="name">
                                        <a href="{{ route('shop.product.details', ['product_slug' => $item->product->slug]) }}" target="_blank"
                                            class="body-title-2">{{ $item->product->name }}</a>
                                    </div>
                                </td>
                                <td class="text-center">BDT: {{ $item->price }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-center">{{ $item->product->SKU }}</td>
                                <td class="text-center">{{ $item->product->category->name }}</td>
                                <td class="text-center">{{ $item->product->brand->name }}</td>
                                <td class="text-center">{{ $item->options }}</td>
                                <td class="text-center">{{ $item->rstatus == 0 ? 'No' : 'Yes' }}</td>
                                <td class="text-center">
                                    <div class="list-icon-function view-icon">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $orderItems->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <div class="wg-box mt-5">
                <h5>Shipping Address</h5>
                <br>
                <div class="my-account__address-item col-md-6">
                    <div class="my-account__address-item__detail">
                        <p>{{ $order->name }}</p>
                        <p>{{ $order->address }}</p>
                        <p>{{ $order->locality }}</p>
                        <p>{{ $order->city }}, {{ $order->country }}</p>
                        <p>{{ $order->landmark }}</p>
                        <p>{{ $order->zip }}</p>
                        <br>
                        <p>Mobile : {{ $order->phone }}</p>
                    </div>
                </div>
            </div>

            <div class="wg-box mt-5">
                <h5>Transactions</h5>
                <table class="table table-striped table-bordered table-transaction">
                    <tbody>
                        <tr>
                            <th>Subtotal</th>
                            <td>{{ $order->subtotal }}</td>
                            <th>Tax</th>
                            <td>{{ $order->tax }}</td>
                            <th>Discount</th>
                            <td>{{ $order->discount }}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>{{ $order->total }}</td>
                            <th>Payment Mode</th>
                            <td>{{ $transaction->mode }}</td>
                            <th>Status</th>
                            <td>
                                @if($transaction->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($transaction->status == 'declined')
                                    <span class="badge bg-danger">Declined</span>
                                @elseif($transaction->status == 'refunded')
                                    <span class="badge bg-danger">Refunded</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="wg-box mt-5 text-right">
                <form action="{{ route('user.order.cancel') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <button type="button" class="btn btn-danger cancel-order">Cancel Order</button>
                </form>
            </div>

        </div>
        
    </div>
</section>
</main>

@endsection

@push('scripts')
<script>
    $(function() {
        $('.cancel-order').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "You want to cancel this order?",
                type: "warning",
                buttons: ["No", "Yes"],
                confirmButtonColor: "#DD6B55",
            }).then(function(result){
                if (result) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush