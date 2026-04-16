<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account Created</title>
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
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 150px;
        }
        h1 {
            color: #4CAF50;
            font-size: 24px;
            margin: 0;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            margin: 15px 0;
        }
        .link {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            background-color: #28a745;
            color: #ffffff;
            font-size: 14px;
            margin-right: 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #777777;
        }
        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <!-- You can add your company logo here -->
            <img src="{{asset('admin')}}/img/logo.png" alt="Company Logo">
        </div>
        <h1>Welcome, {{ $user->name }}!</h1>
        <p>We are excited to inform you that your account has been successfully created. Here are your account details:</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Password:</strong> {{ $user->show_password }}</p>
        <p><strong>Role:</strong> 
            @if(!empty($user->getRoleNames()))
                @foreach($user->getRoleNames() as $v)
                    <span class="badge">{{ $v }}</span>
                @endforeach
            @endif
        </p>
        <p>To log in to your account, please use the following link:</p>
        <p><a href="{{ asset('login') }}" class="link">Login to Your Account</a></p>
        <p class="footer">If you have any questions, feel free to <a href="{{ asset('contact') }}">contact us</a>. <br>© {{ date('Y') }} Vasu Healthcare. All rights reserved.</p>
    </div>
</body>
</html>
