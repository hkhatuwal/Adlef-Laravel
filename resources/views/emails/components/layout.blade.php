<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'First Digital Trust' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #1a2b47;
            padding: 20px;
            text-align: center;
        }
        .logo {
            max-width: 200px;
        }
        .content {
            padding: 20px;
            background-color: #fff;
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 15px;
        }
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
        }
        a {
            color: #0066cc;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        @include('emails.components.header')

        <div class="content">
            <h1>{{ $heading }}</h1>
            {{ $slot }}
        </div>

        @if(isset($showFooter) && $showFooter)
            @include('emails.components.footer')
        @endif
    </div>
</body>
</html>
