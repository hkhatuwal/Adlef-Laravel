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
        
        try {
            // Dispatch the job synchronously for testing
            PollTronTransactions::dispatchSync();
            
            $this->info('TRON transaction polling completed successfully!');
            $this->info('Check the log files for detailed output.');
        } catch (\Exception $e) {
            $this->error('Error running TRON polling job: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 