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

    protected $oppwaService;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
       $this->oppwaService= new OppwaService();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::channel('oppwa')->info('Starting OPPWA transaction polling');

        try {
            $this->markOlderPendingPaymentAsFailed();
            // Get pending transactions from the last 10 minutes
            $pendingTransactions = OppwaTransaction::where('status', OppwaTransaction::STATUS_PENDING)
                ->where('created_at', '>=', now()->subMinutes(30))
                ->whereNotNull('oppwa_checkout_id')
                ->get();

            Log::channel('oppwa')->info('Found pending OPPWA transactions', [
                'count' => $pendingTransactions->count()
            ]);


            foreach ($pendingTransactions as $transaction) {
                try {
                    $statusResult = $this->oppwaService->getPaymentStatus($transaction->oppwa_checkout_id);
                } catch (\Exception $e) {
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

    private function markOlderPendingPaymentAsFailed(): void
    {

        $pendingTransactions = OppwaTransaction::where('status', OppwaTransaction::STATUS_PENDING)
            ->where('created_at', '<=', now()->subMinutes(30))->get();
        foreach ($pendingTransactions as $transaction) {
            $transaction->update(['status' => OppwaTransaction::STATUS_FAILED]);
            $this->oppwaService->callWebhook($transaction);
        }
    }
}
