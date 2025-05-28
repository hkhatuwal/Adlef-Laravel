<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Activity Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #cccccc;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        h1 {
            color: #000000;
            font-size: 24px;
            margin: 0;
            padding: 0;
            font-weight: bold;
        }
        .subtitle {
            color: #666666;
            font-size: 14px;
            margin-top: 5px;
        }
        .stats {
            width: 100%;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f5f5f5;
            border: 1px solid #dddddd;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }
        .stat-item {
            text-align: center;
            padding: 10px;
        }
        .stat-label {
            font-size: 11px;
            color: #666666;
            margin-bottom: 5px;
            display: block;
        }
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #000000;
            display: block;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border: 1px solid #dddddd;
        }
        .main-table th {
            background-color: #f5f5f5;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            color: #333333;
            border-bottom: 2px solid #cccccc;
            border-right: 1px solid #dddddd;
        }
        .main-table td {
            padding: 10px 8px;
            font-size: 11px;
            border-bottom: 1px solid #dddddd;
            border-right: 1px solid #dddddd;
            color: #333333;
            vertical-align: top;
        }
        .activity-type {
            font-weight: bold;
            color: #000000;
            margin-bottom: 3px;
            font-size: 11px;
        }
        .activity-date {
            font-size: 9px;
            color: #666666;
        }
        .counterparty-main {
            font-weight: bold;
            color: #000000;
            margin-bottom: 3px;
            font-size: 11px;
        }
        .counterparty-sub {
            font-size: 9px;
            color: #666666;
        }
        .reference {
            font-weight: bold;
            color: #000000;
            font-size: 11px;
        }
        .status {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            text-align: center;
            min-width: 60px;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .amount-main {
            font-weight: bold;
            color: #000000;
            margin-bottom: 3px;
            font-size: 11px;
        }
        .amount-sub {
            font-size: 9px;
            color: #666666;
        }
        .metadata-row {
            background-color: #f9f9f9;
        }
        .metadata-row td {
            padding: 8px;
            font-size: 9px;
            color: #666666;
            font-style: italic;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666666;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #cccccc;
        }
        .col-type-date { 
            width: 25%; 
        }
        .col-counterparty { 
            width: 25%; 
        }
        .col-reference { 
            width: 16%; 
        }
        .col-status { 
            width: 17%; 
        }
        .col-amount { 
            width: 17%; 
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
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
        <table class="stats-table">
            <tr>
                <td class="stat-item">
                    <span class="stat-label">Total Activities</span>
                    <span class="stat-value">{{ $activities->count() }}</span>
                </td>
                <td class="stat-item">
                    <span class="stat-label">Completed</span>
                    <span class="stat-value">{{ $activities->where('status', 'completed')->count() }}</span>
                </td>
                <td class="stat-item">
                    <span class="stat-label">Pending</span>
                    <span class="stat-value">{{ $activities->where('status', 'pending')->count() }}</span>
                </td>
                <td class="stat-item">
                    <span class="stat-label">Failed</span>
                    <span class="stat-value">{{ $activities->where('status', 'failed')->count() }}</span>
                </td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th class="col-type-date">Type & Date</th>
                <th class="col-counterparty">Counterparty</th>
                <th class="col-reference">Reference No.</th>
                <th class="col-status text-center">Status</th>
                <th class="col-amount text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
                <tr>
                    <!-- Type & Date -->
                    <td class="col-type-date">
                        <div class="activity-type">
                            {{ ucfirst(str_replace('_', ' ', $activity->action ?? $activity->activity_type)) }}
                        </div>
                        <div class="activity-date">
                            {{ $activity->created_at->format('d M Y') }}
                        </div>
                    </td>

                    <!-- Counterparty -->
                    <td class="col-counterparty">
                        <div class="counterparty-main">
                            {{ $activity->description }}
                        </div>
                        @if(isset($activity->metadata['account_no']))
                            <div class="counterparty-sub">
                                {{ $activity->metadata['account_no'] }}
                            </div>
                        @endif
                    </td>

                    <!-- Reference No. -->
                    <td class="col-reference">
                        <div class="reference">
                            {{ $activity->reference_number }}
                        </div>
                    </td>

                    <!-- Status -->
                    <td class="col-status text-center">
                        <span class="status status-{{ $activity->status }}">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </td>

                    <!-- Amount -->
                    <td class="col-amount text-right">
                        @if($activity->amount)
                            <div class="amount-main">
                                {{ number_format($activity->amount, 2) }} {{ $activity->currency_symbol }}
                            </div>
                            @if(isset($activity->metadata['usd_value']))
                                <div class="amount-sub">
                                    ${{ number_format($activity->metadata['usd_value'], 2) }} USD
                                </div>
                            @endif
                        @else
                            <div class="amount-main">-</div>
                        @endif
                    </td>
                </tr>

                <!-- Metadata Row (if exists) -->
                @if($activity->metadata && (isset($activity->metadata['fee']) || isset($activity->metadata['network_fee']) || isset($activity->metadata['exchange_rate'])))
                    <tr class="metadata-row">
                        <td colspan="5">
                            @if(isset($activity->metadata['fee']))
                                <strong>Fee:</strong> {{ number_format($activity->metadata['fee'], 8) }} {{ $activity->currency_symbol }}
                            @endif
                            @if(isset($activity->metadata['network_fee']))
                                @if(isset($activity->metadata['fee'])) | @endif
                                <strong>Network Fee:</strong> {{ number_format($activity->metadata['network_fee'], 8) }} {{ $activity->currency_symbol }}
                            @endif
                            @if(isset($activity->metadata['exchange_rate']))
                                @if(isset($activity->metadata['fee']) || isset($activity->metadata['network_fee'])) | @endif
                                <strong>Rate:</strong> {{ number_format($activity->metadata['exchange_rate'], 8) }}
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report includes activities from {{ request('start_date', $activities->last()?->created_at?->format('Y-m-d') ?? 'N/A') }} 
           to {{ request('end_date', $activities->first()?->created_at?->format('Y-m-d') ?? 'N/A') }}</p>
    </div>
</body>
</html> 