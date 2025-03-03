<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\BannerSetting;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('consent.banner', function ($view) {
            $view->with('bannerSettings', BannerSetting::getDefault());
        });
    }
} 