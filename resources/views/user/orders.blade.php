@extends('layouts.app')

@section('content')

<style>
    /* Background & Container */
    body {
        background-color: #f2f7fb !important;
    }

    .my-account.container {
        max-width: 1400px;
        margin-top: 50px;
    }

    /* Card Wrapper */
    .wg-table.table-all-user {
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.02);
    }

    /* Table Header Styling */
    .table > :not(caption) > tr > th {
        padding: 15px !important;
        background-color: #f7f9fc !important; /* Light grey/blue header */
        color: #2d3748 !important; /* Dark visible text */
        font-weight: 700 !important;
        font-size: 13px;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0 !important;
        text-align: center;
    }

    /* Table Body Styling */
    .table > tbody > tr > td {
        padding: 15px !important;
        vertical-align: middle !important;
        color: #1a202c !important; /* Dark visible text */
        border-color: #e2e8f0 !important;
        font-size: 14px;
    }

    /* Action Icon Styling */
    .view-icon {
        color: #3182ce;
        font-size: 18px;
        transition: transform 0.2s;
    }

    /* Pagination Styling */
    .wgp-pagination {
        margin-top: 20px;
        padding: 10px;
    }

    /* Horizontal Divider */
    .divider {
        height: 1px;
        background: #e2e8f0;
        margin: 20px 0;
    }

    /* Badge Colors (Consistency) */
    .status-delivered { color: #48bb78; font-weight: bold; }
    .status-canceled { color: #f56565; font-weight: bold; }
    .status-pending { color: #ecc94b; font-weight: bold; }
</style>

<main class="pt-90" style="padding-top: 0px;">
<div class="mb-4 pb-4"></div>
<section class="my-account container">
    <h2 class="page-title">Orders</h2>
    <div class="row">
        <div class="col-lg-2">
            @include('user.account-nav')
        </div>

        <div class="col-lg-10">
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 80px">OrderNo</th>
                                <th>Name</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Tax</th>
                                <th class="text-center">Total</th>
                                
                                <th class="text-center">Status</th>
                                <th class="text-center">Order Date</th>
                                <th class="text-center">Items</th>
                                <th class="text-center">Delivered On</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr>
                                <td class="text-center">{{ $order->id }}</td>  
                                <td class="text-center">{{ $order->name }}</td>
                                <td class="text-center">{{ $order->phone }}</td>
                                <td class="text-center">BDT: {{ $order->subtotal }}</td>
                                <td class="text-center">BDT: {{ $order->tax }}</td>
                                <td class="text-center">BDT: {{ $order->total }}</td>
                                
                                <td class="text-center">
                                    @if($order->status == 'delivered')
                                        <span class="badge bg-success">Delivered</span>
                                    @elseif($order->status == 'canceled')
                                        <span class="badge bg-danger">Canceled</span>
                                    @else
                                        <span class="badge bg-warning">Ordered</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $order->created_at }}</td>
                                <td class="text-center">{{ $order->orderItems->count() }}</td>
                                <td class="text-center">{{ $order->delivered_date }}</td>

                                <td class="text-center">
                                    <a href="{{ route('user.order.details', $order->id) }}">
                                    <div class="list-icon-function view-icon">
                                        <div class="item eye">
                                            <i class="fa fa-eye"></i>
                                        </div>                                        
                                    </div>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                                                                
                        </tbody>
                    </table>                
                </div>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                
                {{ $orders->links('pagination::bootstrap-5') }}
                
            </div>
        </div>
        
    </div>
</section>
</main>

@endsection