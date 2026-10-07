@php
    // Root-relative URLs: `php artisan down --render` builds this page on the CLI,
    // where asset() uses APP_URL rather than the host the visitor is on
    $path = fn (string $url) => parse_url($url, PHP_URL_PATH);
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#000000">
    <link rel="icon" href="{{ $path(asset('favicon.ico')) }}" type="image/x-icon">
    <title>Scheduled maintenance | {{ config('app.name') }}</title>
    <style>
        @font-face {
            font-family: 'visulet-light';
            src: url('{{ $path(Vite::asset('resources/fonts/VisueltPro-Light.woff2')) }}') format('woff2');
            font-display: swap;
        }

        @font-face {
            font-family: 'visulet-bold';
            src: url('{{ $path(Vite::asset('resources/fonts/VisueltPro-Bold.ttf')) }}') format('truetype');
            font-display: swap;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #000;
            color: #fff;
            font-family: 'visulet-light', system-ui, -apple-system, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        header, footer {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        header {
            display: flex;
            align-items: center;
            height: 96px;
        }

        header img {
            height: 64px;
            width: auto;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 48px 24px 64px;
        }

        .content {
            max-width: 640px;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px;
            border: 1px solid rgba(190, 242, 100, 0.35);
            border-radius: 999px;
            color: #bef264;
            font-size: 14px;
            letter-spacing: 0.02em;
        }

        .status::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #bef264;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        @media (prefers-reduced-motion: reduce) {
            .status::before {
                animation: none;
            }
        }

        h1 {
            margin: 28px 0 20px;
            font-family: 'visulet-bold', system-ui, -apple-system, 'Segoe UI', sans-serif;
            font-weight: 700;
            font-size: clamp(40px, 7vw, 72px);
            line-height: 1.05;
            letter-spacing: -0.02em;
        }

        p {
            margin: 0;
            color: #a1a1aa;
            font-size: 18px;
            line-height: 1.6;
        }

        .contact {
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid #27272a;
            font-size: 16px;
        }

        .contact a {
            color: #fff;
            text-decoration: underline;
            text-decoration-color: #bef264;
            text-underline-offset: 4px;
        }

        footer {
            padding-bottom: 32px;
            color: #71717a;
            font-size: 14px;
        }
    </style>
</head>
<body>
<header>
    <img src="{{ $path(asset('assets/images/logo-light.svg')) }}?v=2" alt="{{ config('app.name') }}">
</header>

<main>
    <div class="content">
        <span class="status">Scheduled maintenance</span>
        <h1>We'll be back shortly.</h1>
        <p>We're carrying out planned upgrades to the Adlef website and client portal. Everything will be back online soon.</p>
        <p class="contact">Need help in the meantime? Email <a href="mailto:support@adlefgroup.com">support@adlefgroup.com</a></p>
    </div>
</main>

<footer>
    &copy; {{ date('Y') }} Adlef Holding LLC. All rights reserved.
</footer>
</body>
</html>
