<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\ApiClient;
use App\Models\Currency;
use App\Utils\CurrencyConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentTransaction::with(['apiClient.user', 'paymentMethod']);

        // Filter by user (through API client)
        if ($request->filled('user_id')) {
            $query->whereHas('apiClient', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        // Filter by last four digits of card or bank account
        if ($request->filled('last_four')) {
            $query->whereHas('paymentMethod', function ($q) use ($request) {
                $q->where('card_last_four', $request->last_four)
                  ->orWhere('account_last_four', $request->last_four);
            });
        }

        // Filter by payment method type
        if ($request->filled('payment_method_type')) {
            $query->whereHas('paymentMethod', function ($q) use ($request) {
                $q->where('payment_method_type', $request->payment_method_type);
            });
        }

        // Filter by card brand
        if ($request->filled('card_brand')) {
            $query->whereHas('paymentMethod', function ($q) use ($request) {
                $q->where('card_brand', $request->card_brand);
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by gateway
        if ($request->filled('gateway_name')) {
            $query->where('gateway_name', $request->gateway_name);
        }

        // Filter by currency
        if ($request->filled('currency')) {
            $query->where('currency', $request->currency);
        }

        // Search by transaction ID, customer email, or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('client_order_id', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate(20);

        // Get dashboard statistics using the same filtered query
        $statsQuery = clone $query;
        $statsQuery->getQuery()->orders = null; // Remove ordering for stats
        
        // Get total transactions count
        $totalTransactions = $statsQuery->count();
        
        // Get status breakdown
        $statusBreakdown = $statsQuery->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        // Get total amount in USD
        $currencyConverter = new CurrencyConverter();
        $totalAmountUSD = 0;
        
        $amountsByCurrency = $statsQuery->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->get();
        
        foreach ($amountsByCurrency as $amountData) {
            $currency = Currency::where('symbol', $amountData->currency)->first();
            if ($currency) {
                $totalAmountUSD += $currencyConverter->convertToUSD($amountData->total, $currency);
            }
        }
        
        // Get successful transactions count and amount
        $successfulQuery = clone $statsQuery;
        $successfulTransactions = $successfulQuery->where('status', PaymentTransaction::STATUS_COMPLETED)->count();
        
        $successfulAmountsByCurrency = $successfulQuery->where('status', PaymentTransaction::STATUS_COMPLETED)
            ->select('currency', DB::raw('sum(amount) as total'))
            ->groupBy('currency')
            ->get();
        
        $successfulAmountUSD = 0;
        foreach ($successfulAmountsByCurrency as $amountData) {
            $currency = Currency::where('symbol', $amountData->currency)->first();
            if ($currency) {
                $successfulAmountUSD += $currencyConverter->convertToUSD($amountData->total, $currency);
            }
        }
        
        // Get failed transactions count
        $failedTransactions = $statsQuery->where('status', PaymentTransaction::STATUS_FAILED)->count();
        
        // Get pending transactions count
        $pendingTransactions = $statsQuery->whereIn('status', [
            PaymentTransaction::STATUS_CHECKOUT_PENDING,
            PaymentTransaction::STATUS_PENDING,
            PaymentTransaction::STATUS_PROCESSING
        ])->count();

        // Get filter options
        $users = User::role('client')->orderBy('name')->get();
        $paymentMethodTypes = PaymentMethod::distinct()->pluck('payment_method_type')->filter();
        $cardBrands = PaymentMethod::distinct()->pluck('card_brand')->filter();
        $gateways = PaymentTransaction::distinct()->pluck('gateway_name')->filter();
        $currencies = PaymentTransaction::distinct()->pluck('currency')->filter();
        $statuses = [
            PaymentTransaction::STATUS_CHECKOUT_PENDING,
            PaymentTransaction::STATUS_PENDING,
            PaymentTransaction::STATUS_PROCESSING,
            PaymentTransaction::STATUS_COMPLETED,
            PaymentTransaction::STATUS_FAILED,
            PaymentTransaction::STATUS_CANCELLED,
            PaymentTransaction::STATUS_REFUNDED,
        ];

        return view('admin.payment-transactions.index', compact(
            'transactions',
            'users',
            'paymentMethodTypes',
            'cardBrands',
            'gateways',
            'currencies',
            'statuses',
            'totalTransactions',
            'statusBreakdown',
            'totalAmountUSD',
            'successfulTransactions',
            'successfulAmountUSD',
            'failedTransactions',
            'pendingTransactions'
        ));
    }

    public function show(PaymentTransaction $paymentTransaction)
    {
        $paymentTransaction->load(['apiClient.user', 'paymentMethod']);
        
        return view('admin.payment-transactions.show', compact('paymentTransaction'));
    }

    public function export(Request $request)
    {
        // Apply the same filters as index method
        $query = PaymentTransaction::with(['apiClient.user', 'paymentMethod']);

        if ($request->filled('user_id')) {
            $query->whereHas('apiClient', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        if ($request->filled('last_four')) {
            $query->whereHas('paymentMethod', function ($q) use ($request) {
                $q->where('card_last_four', $request->last_four)
                  ->orWhere('account_last_four', $request->last_four);
            });
        }

        if ($request->filled('payment_method_type')) {
            $query->whereHas('paymentMethod', function ($q) use ($request) {
                $q->where('payment_method_type', $request->payment_method_type);
            });
        }

        if ($request->filled('card_brand')) {
            $query->whereHas('paymentMethod', function ($q) use ($request) {
                $q->where('card_brand', $request->card_brand);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gateway_name')) {
            $query->where('gateway_name', $request->gateway_name);
        }

        if ($request->filled('currency')) {
            $query->where('currency', $request->currency);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('client_order_id', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest()->get();

        // Generate CSV
        $filename = 'payment_transactions_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Transaction ID',
                'Client Order ID',
                'User',
                'Customer Email',
                'Customer Name',
                'Amount',
                'Currency',
                'Status',
                'Gateway',
                'Payment Method Type',
                'Card Brand',
                'Card Last Four',
                'Created At',
                'Updated At'
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->transaction_id,
                    $transaction->client_order_id,
                    $transaction->apiClient->user->name ?? 'N/A',
                    $transaction->customer_email,
                    $transaction->customer_name,
                    $transaction->amount,
                    $transaction->currency,
                    $transaction->status,
                    $transaction->gateway_name,
                    $transaction->paymentMethod->payment_method_type ?? 'N/A',
                    $transaction->paymentMethod->card_brand ?? 'N/A',
                    $transaction->paymentMethod->card_last_four ?? 'N/A',
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
