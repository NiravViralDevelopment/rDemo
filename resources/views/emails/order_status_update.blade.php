<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }
        .header {
            background-color: #28a745;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            color: #ffffff;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px 0;
        }
        h2 {
            color: #28a745;
            font-size: 22px;
            margin-top: 0;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            margin: 15px 0;
        }
        .order-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .order-details h3 {
            margin-top: 0;
            font-size: 18px;
            color: #333333;
        }
        .order-details p {
            margin: 5px 0;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777777;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        .footer a {
            color: #28a745;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Status Update</h1>
        </div>
        <div class="content">
            <h2 style="text-align: center;">Hello, {{ $data->client_name }} {{ $data->client_last_name }}!</h2>
            <p>We wanted to inform you that the status of your order has been updated.</p>
            
            <div class="order-details">
                <h3>Order Information:</h3>
                <p><strong>Order ID:</strong> {{ $data->id }}</p>
                <p><strong>VIN: VIN Record ID:</strong> {{ $data->vin_record_number }}</p>
                <p><strong>Brand:</strong> {{ $data->brand }}</p>
                <p><strong>Model Description:</strong> {{ $data->description }}</p>
                <p><strong>Branch:</strong> {{ $data->branch }}</p>
                <p><strong>Engine Type:</strong> {{ $data->engine_type }}</p>
                <p><strong>Transmission:</strong> {{ $data->transmission }}</p>
                <p><strong>VIN Number:</strong> {{ $data->vin_number }}</p>
                <p><strong>Base Model:</strong> {{ $data->base_model }}</p>
                <p><strong>Invoice Date:</strong> {{ $data->invoice_date }}</p>               
                <p><strong>Email:</strong> {{ $data->email }}</p>
                <p><strong>Item:</strong> {{ $data->orderItems->title }}</p>
                <p><strong>Quantity:</strong> {{ $data->quantity }}</p>                
                <p><strong>Status:</strong> {{ $data->status }}</p>
                <div class="col-md-6">
                    <p><strong>Address:</strong> {{ $data->address }}</p>
                </div>
                @if(!empty($data->countries))
                    <div class="col-md-6">
                        <p><strong>Country:</strong> {{ $data->countries->country_name }}</p>
                    </div>
                @endif
                @if(!empty($data->cities))
                    <div class="col-md-6">
                        <p><strong>City:</strong> {{ $data->cities->city_name }}</p>
                    </div>
                @endif

                @if(!empty($data->dispatched_date))                    
                        <p><strong>Dispatched Date:</strong> {{ date('Y-m-d h:i:s A', strtotime($data->dispatched_date)) }}</p>
                @endif
                @if(!empty($data->tracking_number))                    
                        <p><strong>Tracking Number:</strong> {{ $data->tracking_number }}</p>                    
                @endif
            </div>

            <p>If you have any questions or need assistance, feel free to <a href="{{ asset('contact') }}">contact us</a>.</p>
            <p>Thank you for shopping with us!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Vasu Healthcare. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
