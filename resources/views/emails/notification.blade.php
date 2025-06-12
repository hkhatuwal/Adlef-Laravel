<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .notification {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .notification-title {
            color: #1a202c;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .notification-message {
            color: #4a5568;
            margin-bottom: 20px;
        }
        .notification-type {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .type-success { background-color: #def7ec; color: #03543f; }
        .type-warning { background-color: #fef3c7; color: #92400e; }
        .type-error { background-color: #fee2e2; color: #991b1b; }
        .type-info { background-color: #e1effe; color: #1e429f; }
    </style>
</head>
<body>
    <div class="notification">
        <div class="notification-title">{{ $title }}</div>
        
        <div class="notification-type type-{{ $type ?? 'info' }}">
            {{ ucfirst($type ?? 'info') }}
        </div>
        
        <div class="notification-message">
            {!! nl2br(e($notificationMessage ?? $message ?? '')) !!}
        </div>
        
        @if(isset($metadata) && !empty($metadata))
            <div class="notification-metadata">
                @foreach($metadata as $key => $value)
                    <div><strong>{{ ucfirst($key) }}:</strong> {{ is_array($value) ? json_encode($value) : $value }}</div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html> 