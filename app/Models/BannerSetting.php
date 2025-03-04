<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'position',
        'layout',
        'theme',
        'button_style',
        'title',
        'description',
        'accept_button_text',
        'reject_button_text',
        'settings_button_text',
        'show_reject_button',
        'show_settings_button',
        'z_index',
        'padding',
        'margin',
        'border_radius',
        'font_family',
        'font_size',
        'primary_color',
        'secondary_color',
        'background_color',
        'text_color',
        'cookie_categories',
        'is_active'
    ];

    protected $casts = [
        'cookie_categories' => 'array',
        'show_reject_button' => 'boolean',
        'show_settings_button' => 'boolean',
        'is_active' => 'boolean',
        'z_index' => 'integer',
        'padding' => 'integer',
        'margin' => 'integer'
    ];

    public static function getDefaultSettings()
    {
        $defaultSettings = [
            'position' => 'bottom',
            'layout' => 'bar',
            'theme' => 'light',
            'button_style' => 'filled',
            'title' => 'Cookie Consent',
            'description' => 'We use cookies to enhance your browsing experience and analyze our traffic.',
            'accept_button_text' => 'Accept All',
            'reject_button_text' => 'Reject All',
            'settings_button_text' => 'Cookie Settings',
            'show_reject_button' => true,
            'show_settings_button' => true,
            'z_index' => 1000,
            'padding' => 20,
            'margin' => 20,
            'border_radius' => '4px',
            'font_family' => 'inherit',
            'font_size' => '14px',
            'primary_color' => '#4CAF50',
            'secondary_color' => '#2196F3',
            'background_color' => '#ffffff',
            'text_color' => '#000000',
            'cookie_categories' => [
                'necessary' => [
                    'name' => 'Necessary',
                    'description' => 'These cookies are essential for the website to function properly.',
                    'required' => true
                ],
                'statistics' => [
                    'name' => 'Statistics',
                    'description' => 'These cookies help us understand how visitors interact with our website.',
                    'required' => false
                ],
                'marketing' => [
                    'name' => 'Marketing',
                    'description' => 'These cookies are used to track visitors across websites.',
                    'required' => false
                ],
                'preferences' => [
                    'name' => 'Preferences',
                    'description' => 'These cookies remember your settings and preferences.',
                    'required' => false
                ]
            ],
            'is_active' => true
        ];

        // Try to get existing settings
        $settings = self::first();

        // If no settings exist, create new ones
        if (!$settings) {
            $settings = self::create($defaultSettings);
        }

        return $settings;
    }
}
