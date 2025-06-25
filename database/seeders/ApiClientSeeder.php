<?php

namespace Database\Seeders;

use App\Models\ApiClient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApiClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {




        // Create a demo API client for testing
        $credentials = ApiClient::generateCredentials();

        $demoUser=User::find(2);
        ApiClient::create([
            'user_id' => $demoUser->id,
            'name' => 'Demo Client API',
            'email' => "himtech728@gmail.com",
            'company_name' => 'Demo Company Ltd',
            'api_key' => $credentials['api_key'],
            'secret_key' => $credentials['secret_key'],
            'is_active' => true,
            'is_sandbox' => true,
            'allowed_currencies' => ['USD', 'EUR', 'GBP'],
            'daily_limit' => 10000.00,
            'monthly_limit' => 100000.00,
            'webhook_urls' => [
                'https://webhook.site/demo-webhook'
            ],
        ]);



        $this->command->info('API Clients created successfully!');
        $this->command->info('Demo User: ' . $demoUser->email . ' (ID: ' . $demoUser->id . ')');
        $this->command->info('Demo Client API Key: ' . $credentials['api_key']);
        $this->command->info('Demo Client Secret Key: ' . $credentials['secret_key']);
    }
}
