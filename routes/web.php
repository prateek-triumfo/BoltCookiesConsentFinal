<?php

use App\Http\Controllers\Admin\ConsentCategoryController;
use App\Http\Controllers\Admin\ConsentLogController;
use App\Http\Controllers\ConsentController;
use App\Http\Controllers\ProfileController;
use App\http\controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
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
    Route::prefix('consent')->name('consent.')->group(function () {
        // Consent Categories
        Route::resource('categories', ConsentCategoryController::class);
        
        // Consent Logs
        Route::get('logs', [ConsentLogController::class, 'index'])->name('logs.index');
        Route::get('logs/{log}', [ConsentLogController::class, 'show'])->name('logs.show');
        Route::get('logs-export', [ConsentLogController::class, 'export'])->name('logs.export');
    });
});





require __DIR__.'/auth.php';
