<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #f0f0f0;
        }
        .header img {
            max-width: 150px;
            height: auto;
        }
        .content {
            padding: 30px 20px;
            text-align: center;
        }
        .otp-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 32px;
            letter-spacing: 5px;
            font-weight: bold;
            color: #333;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #f0f0f0;
        }
        .warning {
            color: #dc3545;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
        </div>
        <div class="content">
            <h2>Verify Your Email</h2>
            <p>Hello!</p>
            <p>Your email verification code is:</p>
            <div class="otp-box">
                {{ $otp }}
            </div>
            <p>Please enter this code in the verification page to complete your email verification.</p>
            <p class="warning">This code will expire in 15 minutes.</p>
            <p>If you didn't request this code, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>This is an automated message, please do not reply.</p>
            <p>&copy; {{ date('Y') }} Adlef. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
