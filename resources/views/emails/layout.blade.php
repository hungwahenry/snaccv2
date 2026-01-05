<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'snacc')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Sora', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #ffffff;
            padding: 40px 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 48px;
        }
        .logo {
            font-size: 48px;
            font-weight: bold;
            color: #e63946;
            margin-bottom: 8px;
            text-transform: lowercase;
        }
        .tagline {
            color: #9ca3af;
            font-size: 14px;
            text-transform: lowercase;
        }
        .content {
            margin-bottom: 48px;
        }
        .content p {
            margin-bottom: 16px;
            color: #6b7280;
            font-size: 16px;
            text-transform: lowercase;
        }
        .otp-code {
            background-color: #fef2f2;
            border: 3px solid #e63946;
            border-radius: 24px;
            padding: 32px;
            text-align: center;
            font-size: 48px;
            font-weight: bold;
            letter-spacing: 12px;
            color: #e63946;
            margin: 32px 0;
        }
        .info-text {
            text-align: center;
            color: #9ca3af;
            font-size: 14px;
            margin: 24px 0;
            text-transform: lowercase;
        }
        .footer {
            text-align: center;
            color: #d1d5db;
            font-size: 12px;
            margin-top: 48px;
            padding-top: 32px;
            border-top: 2px solid #f3f4f6;
            text-transform: lowercase;
        }
        .footer p {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">snacc</div>
            <p class="tagline">campus social network</p>
        </div>

        <div class="content">
            @yield('content')
        </div>

        <div class="footer">
            <p>this is an automated email from snacc.</p>
            <p>&copy; {{ date('Y') }} snacc. all rights reserved.</p>
        </div>
    </div>
</body>
</html>
