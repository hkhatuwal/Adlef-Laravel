<?php

namespace App\Jobs;

use App\Models\OppwaTransaction;
use App\Services\OppwaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PollOppwaTransactions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::channel('oppwa')->info('Starting OPPWA transaction polling');

        try {
            // Get pending transactions from the last 10 minutes
            $pendingTransactions = OppwaTransaction::where('status', OppwaTransaction::STATUS_PENDING)
                ->where('created_at', '>=', now()->subMinutes(100))
                ->whereNotNull('oppwa_checkout_id')
                ->get();

            Log::channel('oppwa')->info('Found pending OPPWA transactions', [
                'count' => $pendingTransactions->count()
            ]);

            $oppwaService = new OppwaService();

            foreach ($pendingTransactions as $transaction) {
                try {
                    Log::channel('oppwa')->info('Checking transaction status', [
                        'transaction_id' => $transaction->transaction_id,
                        'oppwa_checkout_id' => $transaction->oppwa_checkout_id
                    ]);

                    // Get payment status from OPPWA
                    $statusResult = $oppwaService->getPaymentStatus($transaction->oppwa_checkout_id);
                    if ($transaction->callback_url) {
                        $webhookResult = $oppwaService->callWebhook($transaction);
                        if (!$webhookResult['success']) {
                            Log::channel('oppwa')->error('Webhook call failed', [
                                'transaction_id' => $transaction->transaction_id,
                                'error' => $webhookResult['error'] ?? 'Unknown error'
                            ]);
                        }
                    }


                } catch (\Exception $e) {
                    Log::channel('oppwa')->error('Error processing transaction', [
                        'transaction_id' => $transaction->transaction_id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            Log::channel('oppwa')->info('OPPWA transaction polling completed');

        } catch (\Exception $e) {
            Log::channel('oppwa')->error('OPPWA polling job failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

}
