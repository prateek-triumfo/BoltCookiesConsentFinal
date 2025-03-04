<?php

use App\Http\Controllers\Admin\ConsentCategoryController;
use App\Http\Controllers\Admin\ConsentLogController;
use App\Http\Controllers\ConsentController;
use App\Http\Controllers\ProfileController;
use App\http\controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\BannerSettingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Consent Management Routes
Route::prefix('consent')->name('consent.')->group(function () {
    Route::get('/banner', [ConsentController::class, 'index'])->name('banner');
    Route::post('/save', [ConsentController::class, 'saveConsent'])->name('save');
    Route::get('/preferences', [ConsentController::class, 'getConsent'])->name('preferences');
    Route::get('/manage', [ConsentController::class, 'manage'])->name('manage');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Banner Settings
    Route::get('/banner/settings', [BannerSettingController::class, 'edit'])->name('banner.edit');
    Route::post('/banner/settings', [BannerSettingController::class, 'update'])->name('banner.update');
    Route::get('/banner/preview', [BannerSettingController::class, 'preview'])->name('banner.preview');

    // Consent Categories
    Route::resource('categories', ConsentCategoryController::class);

    // Domains
    Route::resource('domains', DomainController::class);
    Route::post('domains/{domain}/regenerate-api-key', [DomainController::class, 'regenerateApiKey'])->name('domains.regenerate-api-key');
    Route::post('domains/{domain}/regenerate-script-id', [DomainController::class, 'regenerateScriptId'])->name('domains.regenerate-script-id');
    Route::get('domains/{domain}/embed-code', [DomainController::class, 'getEmbedCode'])->name('domains.embed-code');

    // Consent Logs
    Route::get('consent/logs', [ConsentLogController::class, 'index'])->name('consent.logs.index');
    Route::get('consent/logs/{log}', [ConsentLogController::class, 'show'])->name('consent.logs.show');
    Route::get('consent/logs-export', [ConsentLogController::class, 'export'])->name('consent.logs.export');
});

require __DIR__.'/auth.php';
