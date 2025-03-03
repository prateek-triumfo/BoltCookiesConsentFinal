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
        'primary_color',
        'secondary_color',
        'text_color',
        'background_color',
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
        'is_active'
    ];

    protected $casts = [
        'show_reject_button' => 'boolean',
        'show_settings_button' => 'boolean',
        'is_active' => 'boolean',
        'z_index' => 'integer',
        'padding' => 'integer',
        'margin' => 'integer',
    ];

    public static function getDefault()
    {
        return self::firstOrCreate([], [
            'position' => 'bottom',
            'layout' => 'bar',
            'theme' => 'light',
            'primary_color' => '#007bff',
            'secondary_color' => '#6c757d',
            'text_color' => '#212529',
            'background_color' => '#ffffff',
            'button_style' => 'filled',
            'title' => 'Cookie Consent',
            'description' => 'We use cookies to enhance your browsing experience and analyze our traffic. By clicking "Accept All", you consent to our use of cookies.',
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
            'is_active' => true
        ]);
    }
}
