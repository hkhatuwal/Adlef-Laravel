<?php

namespace App\Console\Commands;

use App\Jobs\PollTronTransactions;
use Illuminate\Console\Command;

class TestTronPollingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tron:poll-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test TRON transaction polling job manually';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting TRON transaction polling test...');


        // Dispatch the job synchronously for testing
        PollTronTransactions::dispatch();
//            PollTronTransactions::dispatchSync();


        return 0;
    }
}
