<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HelpCenterCategory;

class HelpCenterCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Account Issues',
                'description' => 'Problems with account access, login, or account settings',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Technical Support',
                'description' => 'Technical issues with the platform, bugs, or system errors',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Billing & Payments',
                'description' => 'Questions about billing, payments, invoices, or subscription issues',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Feature Requests',
                'description' => 'Suggestions for new features or improvements to existing functionality',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'General Inquiry',
                'description' => 'General questions or inquiries that don\'t fit other categories',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Security Concerns',
                'description' => 'Security-related issues, suspicious activity, or privacy concerns',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            HelpCenterCategory::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
