<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerSettingController;
use App\Http\Controllers\Api\ConsentCategoryController;
use App\Http\Controllers\Api\ConsentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Consent Categories
Route::get('/categories', [ConsentCategoryController::class, 'index']);

// Banner Settings
Route::get('/banner-settings/{domain}', [BannerSettingController::class, 'show']);

// Consent Management
Route::post('/consent/save', [ConsentController::class, 'save']);
Route::get('/consent/{cookie_id}', [ConsentController::class, 'show']);

// Legacy Consent Routes (if needed)
Route::group(['prefix' => 'consent'], function () {
    Route::post('save', [ConsentController::class, 'saveConsent']);
    Route::get('get', [ConsentController::class, 'getConsent']);
}); 