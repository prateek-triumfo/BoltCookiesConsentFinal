<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ConsentCategory;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
 // Share categories with the 'banner' view (or any view that needs it)
 View::composer('consent.banner', function ($view) {
    $view->with('categories', ConsentCategory::all());
});    }
}
