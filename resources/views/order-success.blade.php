<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed | StyleBee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: #f8f9fa; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            font-family: 'Segoe UI', sans-serif; 
        }
        .confirmation-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            max-width: 500px;
            margin: auto;
            text-align: center;
        }
        .icon-circle {
            width: 70px;
            height: 70px;
            background: #d4edda;
            color: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
        }
        .tran-id {
            background: #f1f3f5;
            padding: 10px;
            border-radius: 8px;
            font-family: monospace;
            font-weight: bold;
        }
        .btn-stylebee {
            background: #000;
            color: #fff;
            border-radius: 10px;
            padding: 12px 30px;
            text-decoration: none;
            transition: 0.3s;
        }
        .btn-stylebee:hover {
            background: #333;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="confirmation-card">
    <div class="icon-circle">✓</div>
    <h2 class="fw-bold mb-3">Order Confirmed!</h2>
    <p class="text-muted">Thank you for shopping with StyleBee. Your payment was successful and we are preparing your order.</p>
    
    <div class="my-4">
        <small class="text-uppercase text-muted d-block mb-1">Transaction ID</small>
        <div class="tran-id">{{ $tran_id }}</div>
    </div>

    <a href="{{ url('/') }}" class="btn-stylebee">Back to Shop</a>
</div>

</body>
</html>