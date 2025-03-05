<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\BannerSetting;
use App\Models\Domain;
use Illuminate\Support\Str;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('consent.banner', function ($view) {
            // Get the current domain from the request
            $domain = Domain::where('name', request()->getHost())->first();
            
            if ($domain) {
                $bannerSettings = $domain->bannerSetting ?? BannerSetting::getDefaultSettings($domain->id);
            } else {
                // If no domain is found, create a temporary domain and get default settings
                $domain = Domain::create([
                    'name' => request()->getHost(),
                    'description' => 'Auto-generated domain',
                    'api_key' => Str::random(32),
                    'script_id' => Str::random(16)
                ]);
                $bannerSettings = BannerSetting::getDefaultSettings($domain->id);
            }
            
            $view->with('bannerSettings', $bannerSettings);
        });
    }
} 