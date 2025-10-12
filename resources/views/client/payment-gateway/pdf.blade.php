<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Gateway Transactions Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #000;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #000;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #000;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-refunded {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment Gateway Transactions Report</h1>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
        @if($user)
            <p>User: {{ $user->name }} ({{ $user->email }})</p>
        @endif
    </div>

    <div class="info-section">
        <p><strong>Report Date:</strong> {{ now()->format('M j, Y') }}</p>
        <p><strong>Total Transactions:</strong> {{ $transactions->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Payment Method</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_id }}</td>
                    <td>{{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}</td>
                    <td>
                        @php
                            $statusClass = match($transaction->status) {
                                'completed' => 'status-completed',
                                'pending' => 'status-pending',
                                'processing' => 'status-processing',
                                'failed' => 'status-failed',
                                'refunded' => 'status-refunded',
                                default => 'status-pending',
                            };
                        @endphp
                        <span class="status {{ $statusClass }}">{{ strtoupper($transaction->status) }}</span>
                    </td>
                    <td>{{ ucfirst(str_replace('_', ' ', $transaction->payment_method ?? '-')) }}</td>
                    <td>{{ $transaction->created_at->format('M j, Y g:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">No transactions found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This is an automatically generated report. For any queries, please contact support.</p>
        <p>&copy; {{ now()->year }} Payment Gateway. All rights reserved.</p>
    </div>
</body>
</html>

