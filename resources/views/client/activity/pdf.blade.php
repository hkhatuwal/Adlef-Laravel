<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Activity Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1f2937;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        h1 {
            color: #111827;
            font-size: 24px;
            margin: 0;
            padding: 0;
        }
        .subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-top: 5px;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f9fafb;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }
        td {
            padding: 12px;
            font-size: 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #4b5563;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        .status-completed {
            background-color: #def7ec;
            color: #03543f;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-failed {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($logo = config('app.logo'))
            <img src="{{ $logo }}" alt="Logo" class="logo">
        @endif
        <h1>Activity Report</h1>
        <p class="subtitle">Generated on {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-label">Total Activities</div>
            <div class="stat-value">{{ $activities->count() }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Completed</div>
            <div class="stat-value">{{ $activities->where('status', 'completed')->count() }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $activities->where('status', 'pending')->count() }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Failed</div>
            <div class="stat-value">{{ $activities->where('status', 'failed')->count() }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>Type</th>
                <th>Reference</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $activity->created_at->format('M d, Y h:i A') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}</td>
                    <td>{{ $activity->reference_number }}</td>
                    <td>
                        @if($activity->amount)
                            {{ number_format($activity->amount, 8) }} {{ $activity->currency_symbol }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <span class="status status-{{ $activity->status }}">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </td>
                </tr>
                @if($activity->metadata)
                    <tr>
                        <td colspan="5" style="padding-left: 24px; font-size: 11px;">
                            @if(isset($activity->metadata['fee']))
                                <strong>Fee:</strong> {{ number_format($activity->metadata['fee'], 8) }} {{ $activity->currency_symbol }}
                            @endif
                            @if(isset($activity->metadata['network_fee']))
                                <strong>Network Fee:</strong> {{ number_format($activity->metadata['network_fee'], 8) }} {{ $activity->currency_symbol }}
                            @endif
                            @if(isset($activity->metadata['exchange_rate']))
                                <strong>Rate:</strong> {{ number_format($activity->metadata['exchange_rate'], 8) }}
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report includes activities from {{ request('start_date', $activities->last()->created_at->format('Y-m-d')) }} 
           to {{ request('end_date', $activities->first()->created_at->format('Y-m-d')) }}</p>
    </div>
</body>
</html> 