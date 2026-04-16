<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }
        .email-container {
            background-color: #fff;
            padding: 20px;
            margin: 30px auto;
            border-radius: 8px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }
        .header {
            background-color: #007bff;
            padding: 10px;
            text-align: center;
            color: #fff;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
        }
        .content h2 {
            color: #007bff;
        }
        .content p {
            margin: 10px 0;
        }
        .order-details {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
        }
        .order-details p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Order Status Update</h1>
        </div>
        <div class="content">
            <h2>Hello, {{ $data->client_name }}!</h2>
            <p>We wanted to let you know that your order status has been updated.</p>
            
            <div class="order-details">
                <h3>Order Information:</h3>
                <p><strong>Order ID:</strong> {{ $data->order_id }}</p>
                <p><strong>Email:</strong>    {{ $data->email }}</p>
                <p><strong>Status:</strong>   {{ $data->status }}</p>
            </div>

            <p>If you have any questions or need assistance, please feel free to contact us.</p>
            <p>Thank you for shopping with us!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Company Name. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
