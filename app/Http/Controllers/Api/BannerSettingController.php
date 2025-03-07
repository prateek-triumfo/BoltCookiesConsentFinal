<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\BannerSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BannerSettingController extends Controller
{
    public function show($domain)
    {
        try {
            Log::info('Fetching banner settings for domain: ' . $domain);

            // Clean up domain name
            $domainName = strtolower(trim($domain));
            $domainName = preg_replace('#^https?://#', '', $domainName);
            $domainName = preg_replace('#^www\.#', '', $domainName);
            $domainName = rtrim($domainName, '/');

            Log::info('Cleaned domain name: ' . $domainName);

            // Find domain
            $domainModel = Domain::where(function($query) use ($domainName) {
                $query->where('name', 'LIKE', '%' . $domainName . '%')
                      ->orWhere('name', 'LIKE', '%http://' . $domainName . '%')
                      ->orWhere('name', 'LIKE', '%https://' . $domainName . '%')
                      ->orWhere('name', 'LIKE', '%' . $domainName . '/%');
            })->first();

            if (!$domainModel) {
                Log::warning('Domain not found: ' . $domainName);
                return response()->json([
                    'success' => false,
                    'error' => 'Domain not found: ' . $domainName
                ], 404);
            }

            Log::info('Found domain: ' . $domainModel->name);

            // Get or create banner settings
            $bannerSetting = BannerSetting::firstOrCreate(
                ['domain_id' => $domainModel->id],
                [
                    'banner_title' => 'Cookie Consent',
                    'banner_description' => 'We use cookies to enhance your browsing experience and analyze our traffic.',
                    'accept_button_text' => 'Accept All',
                    'reject_button_text' => 'Reject All',
                    'manage_button_text' => 'Manage Settings',
                    'save_button_text' => 'Save Preferences',
                    'cancel_button_text' => 'Cancel',
                    'primary_color' => '#4CAF50',
                    'secondary_color' => '#f44336',
                    'text_color' => '#333333',
                    'background_color' => '#ffffff',
                    'font_family' => 'Arial, sans-serif',
                    'font_size' => '14px',
                    'show_reject_button' => true,
                    'show_manage_button' => true,
                    'show_settings_button' => true,
                    'button_position' => 'right',
                ]
            );

            // Format settings for frontend
            $settings = [
                'title' => $bannerSetting->banner_title,
                'description' => $bannerSetting->banner_description,
                'accept_button_text' => $bannerSetting->accept_button_text,
                'reject_button_text' => $bannerSetting->reject_button_text,
                'manage_button_text' => $bannerSetting->manage_button_text,
                'save_button_text' => $bannerSetting->save_button_text,
                'cancel_button_text' => $bannerSetting->cancel_button_text,
                'show_reject_button' => (bool)$bannerSetting->show_reject_button,
                'show_manage_button' => (bool)$bannerSetting->show_manage_button,
                'show_settings_button' => (bool)$bannerSetting->show_settings_button,
                'style' => [
                    'backgroundColor' => $bannerSetting->background_color,
                    'textColor' => $bannerSetting->text_color,
                    'primaryColor' => $bannerSetting->primary_color,
                    'secondaryColor' => $bannerSetting->secondary_color,
                    'fontFamily' => $bannerSetting->font_family,
                    'fontSize' => $bannerSetting->font_size,
                    'position' => $bannerSetting->button_position
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'domain' => $domainModel->name,
                    'settings' => $settings
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in BannerSettingController@show: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while fetching banner settings',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 