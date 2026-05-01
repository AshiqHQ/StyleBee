@extends('layouts.app')

@section('content')

<style>
    /* Background & Container */
    body { background-color: #f2f7fb !important; }
    
    .my-account.container {
        max-width: 1400px;
        margin-top: 50px;
    }

    /* Welcome Box */
    .welcome-box {
        background: linear-gradient(135deg, #b4ccf3 0%, #ee6c48 100%);
        padding: 50px;
        border-radius: 15px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
    }

    .welcome-box h4 { font-weight: 800; margin-bottom: 10px; }
    .welcome-box p { opacity: 0.9; font-size: 16px; }

    /* Dashboard Cards */
    .dashboard-card {
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        border: 1px solid #e2e8f0;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none !important;
        display: block;
        height: 100%;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.05);
        border-color: #3b82f6;
    }

    .card-icon {
        width: 60px;
        height: 60px;
        background: #eff6ff;
        color: #3b82f6;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 24px;
    }

    .dashboard-card h5 {
        color: #1a202c;
        font-weight: 700;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .dashboard-card p {
        color: #718096;
        font-size: 14px;
        margin-bottom: 0;
    }

</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">My Account</h2>
        <div class="row">
            <div class="col-lg-3">
                @include('user.account-nav')
            </div>
            
            <div class="col-lg-9">
                <div class="welcome-box">
                    <h4>Welcome back, {{ Auth::user()->name }}! 👋</h4>
                    <p>From your dashboard, you can easily control your orders, addresses, and account security.</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <a href="{{ route('user.orders') }}" class="dashboard-card">
                            <div class="card-icon"><i class="fa fa-shopping-bag"></i></div>
                            <h5>Orders</h5>
                            <p>Track, return, or buy items again.</p>
                        </a>
                    </div>
                    
                    <div class="col-md-4">
                        <a href="account_edit_address.html" class="dashboard-card">
                            <div class="card-icon"><i class="fa fa-map-marker"></i></div>
                            <h5>Addresses</h5>
                            <p>Edit shipping and billing addresses.</p>
                        </a>
                    </div>
                    
                    <div class="col-md-4">
                        <a href="account_edit.html" class="dashboard-card">
                            <div class="card-icon"><i class="fa fa-user"></i></div>
                            <h5>Account Details</h5>
                            <p>Update your profile and password.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection