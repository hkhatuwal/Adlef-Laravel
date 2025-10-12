<?php

namespace App\Exports;

use App\Models\PaymentTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class PaymentTransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;
    protected $userId;

    public function __construct(Request $request, $userId)
    {
        $this->request = $request;
        $this->userId = $userId;
    }

    public function collection()
    {
        // Get user's API client IDs
        $apiClientIds = \App\Models\User::find($this->userId)->apiClients()->pluck('id')->toArray();

        if (empty($apiClientIds)) {
            return collect([]);
        }

        $query = PaymentTransaction::query()
            ->whereIn('api_client_id', $apiClientIds)
            ->with(['apiClient:id,name'])
            ->latest();

        // Apply filters from request
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('currency')) {
            $query->where('currency', $this->request->currency);
        }

        if ($this->request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $this->request->from_date);
        }

        if ($this->request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $this->request->to_date);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'API Client',
            'Amount',
            'Currency',
            'Status',
            'Payment Method',
            'Gateway',
            'Customer Email',
            'Description',
            'Created At',
            'Updated At',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->transaction_id,
            $transaction->apiClient ? $transaction->apiClient->name : '-',
            number_format($transaction->amount, 2),
            $transaction->currency,
            ucfirst($transaction->status),
            ucfirst(str_replace('_', ' ', $transaction->payment_method ?? '-')),
            $transaction->gateway_name ?? '-',
            $transaction->customer_email ?? '-',
            $transaction->description ?? '-',
            $transaction->created_at->format('Y-m-d H:i:s'),
            $transaction->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

