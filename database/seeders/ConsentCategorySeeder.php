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
                'description' => 'Necessary cookies help make a website usable by enabling basic functions like page navigation and access to secure areas of the website. The website cannot function properly without these cookies.',
                'is_required' => true,
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'Statistics',
                'key' => 'statistics',
                'description' => 'Statistic cookies help website owners to understand how visitors interact with websites by collecting and reporting information anonymously.',
                'is_required' => false,
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'Marketing',
                'key' => 'marketing',
                'description' => 'Marketing cookies are used to track visitors across websites. The intention is to display ads that are relevant and engaging for the individual user and thereby more valuable for publishers and third party advertisers.',
                'is_required' => false,
                'is_active' => true,
                'display_order' => 3,
            ],
            [
                'name' => 'Preferences',
                'key' => 'preferences',
                'description' => 'Preference cookies enable a website to remember information that changes the way the website behaves or looks, like your preferred language or the region that you are in.',
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