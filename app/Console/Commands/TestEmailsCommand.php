<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtcTransferSuccess;
use App\Mail\OtcTransferFailed;
use App\Mail\OtcTransferCreated;
use App\Mail\TransferOutSuccess;
use App\Mail\TransferOutFailed;
use App\Mail\TransferinSuccess;
use App\Mail\TransferinFailed;
use App\Mail\NewBankAccountAdded;

class TestEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:emails {email? : The email address to send test emails to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test all email templates by sending them to a specified email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'prajapathimanshu51@gmail.com';

        $this->info("Starting to send test emails to: {$email}");

        $emailClasses = [
            OtcTransferSuccess::class => 'OTC Transfer Success',
            OtcTransferFailed::class => 'OTC Transfer Failed',
            OtcTransferCreated::class => 'OTC Transfer Created',
            TransferOutSuccess::class => 'Transfer Out Success',
            TransferOutFailed::class => 'Transfer Out Failed',
            TransferinSuccess::class => 'Transfer In Success',
            TransferinFailed::class => 'Transfer In Failed',
            NewBankAccountAdded::class => 'New Bank Account Added',
        ];

        $bar = $this->output->createProgressBar(count($emailClasses));
        $bar->start();

        foreach ($emailClasses as $class => $description) {
            try {
                Mail::to($email)->send(new $class());
                $this->newLine();
                $this->info("✓ Sent: {$description}");
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("✗ Failed to send {$description}: {$e->getMessage()}");
            }

            $bar->advance();
            // Add a small delay to prevent email server throttling
            sleep(1);
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('All test emails have been sent!');
    }
}
