<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_id',
        'banner_title',
        'banner_description',
        'primary_color',
        'text_color',
        'accept_color',
        'reject_color',
        'manage_color',
        'save_color',
        'cancel_color',
        'font_family',
        'font_size',
        'accept_all_text',
        'reject_all_text',
        'manage_settings_text',
        'save_preferences_text',
        'cancel_text',
        'necessary_cookie_title',
        'statistics_cookie_title',
        'marketing_cookie_title',
        'preferences_cookie_title',
        'necessary_cookie_description',
        'statistics_cookie_description',
        'marketing_cookie_description',
        'preferences_cookie_description',
        'show_reject_button',
        'show_settings_button',
        'show_categories_menu',
        'show_statistics_category',
        'show_marketing_category',
        'show_preferences_category',
        'manage_button_position',
    ];

    protected $casts = [
        'show_reject_button' => 'boolean',
        'show_settings_button' => 'boolean',
        'show_categories_menu' => 'boolean',
        'show_statistics_category' => 'boolean',
        'show_marketing_category' => 'boolean',
        'show_preferences_category' => 'boolean',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public static function getDefaultSettings($domainId)
    {
        return [
            'domain_id' => $domainId,
            'banner_title' => 'We value your privacy',
            'banner_description' => 'We use cookies to enhance your browsing experience, serve personalized content, and analyze our traffic. By clicking "Accept All", you consent to our use of cookies.',
            'primary_color' => '#2563eb',
            'text_color' => '#000000',
            'accept_color' => '#16a34a',
            'reject_color' => '#dc2626',
            'manage_color' => '#4b5563',
            'save_color' => '#2563eb',
            'cancel_color' => '#6b7280',
            'font_family' => 'Arial',
            'font_size' => '14px',
            'accept_all_text' => 'Accept All',
            'reject_all_text' => 'Reject All',
            'manage_settings_text' => 'Manage Settings',
            'save_preferences_text' => 'Save Preferences',
            'cancel_text' => 'Cancel',
            'necessary_cookie_title' => 'Necessary Cookies',
            'statistics_cookie_title' => 'Statistics Cookies',
            'marketing_cookie_title' => 'Marketing Cookies',
            'preferences_cookie_title' => 'Preferences Cookies',
            'necessary_cookie_description' => 'These cookies are essential for the website to function properly.',
            'statistics_cookie_description' => 'These cookies help us understand how visitors interact with the website.',
            'marketing_cookie_description' => 'These cookies are used to deliver personalized advertisements.',
            'preferences_cookie_description' => 'These cookies allow the website to remember choices you make.',
            'show_reject_button' => true,
            'show_settings_button' => true,
            'show_categories_menu' => true,
            'show_statistics_category' => true,
            'show_marketing_category' => true,
            'show_preferences_category' => true,
            'manage_button_position' => 'right',
        ];
    }
}
