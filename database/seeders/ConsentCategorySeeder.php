<?php

namespace Database\Seeders;

use App\Models\ConsentCategory;
use Illuminate\Database\Seeder;

class ConsentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Necessary',
                'key' => 'necessary',
                'description' => 'These cookies are essential for the website to function properly and cannot be disabled.',
                'is_required' => true,
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'Preferences',
                'key' => 'preferences',
                'description' => 'These cookies allow the website to remember choices you make and provide enhanced, personalized features.',
                'is_required' => false,
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'Analytics',
                'key' => 'analytics',
                'description' => 'These cookies help us understand how visitors interact with the website, helping us improve our services.',
                'is_required' => false,
                'is_active' => true,
                'display_order' => 3,
            ],
            [
                'name' => 'Marketing',
                'key' => 'marketing',
                'description' => 'These cookies are used to track visitors across websites to display relevant advertisements.',
                'is_required' => false,
                'is_active' => true,
                'display_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            ConsentCategory::updateOrCreate(
                ['key' => $category['key']],
                $category
            );
        }
    }
}