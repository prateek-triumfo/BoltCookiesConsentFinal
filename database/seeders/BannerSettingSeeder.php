<?php

namespace Database\Seeders;

use App\Models\BannerSetting;
use Illuminate\Database\Seeder;

class BannerSettingSeeder extends Seeder
{
    public function run()
    {
        // Check if settings already exist
        if (!BannerSetting::exists()) {
            // Create new settings using the model's getDefaultSettings method
            BannerSetting::getDefaultSettings();
        }
    }
} 