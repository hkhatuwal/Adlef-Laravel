<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'global_notification',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'Enable Global Notification',
                'description' => 'Show a notification banner to all users',
                'is_active' => true
            ],
            [
                'key' => 'global_notification_text',
                'value' => 'Welcome to our platform! We\'re here to help you succeed.',
                'type' => 'text',
                'group' => 'notifications',
                'label' => 'Global Notification Message',
                'description' => 'The message to display in the global notification banner',
                'is_active' => true
            ],
            // OTC Transaction Cost Settings
            [
                'key' => 'otc_cost_type',
                'value' => 'percentage',
                'type' => 'string'
            ],
            [
                'key' => 'otc_cost_percentage',
                'value' => '0',
                'type' => 'decimal'
            ],
            [
                'key' => 'otc_cost_fixed',
                'value' => '0',
                'type' => 'decimal'
            ],
            // Asset Transfer Cost Settings
            [
                'key' => 'transfer_cost_type',
                'value' => 'percentage',
                'type' => 'string'
            ],
            [
                'key' => 'transfer_cost_percentage',
                'value' => '0',
                'type' => 'decimal'
            ],
            [
                'key' => 'transfer_cost_fixed',
                'value' => '0',
                'type' => 'decimal'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
