<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerSetting;
use Illuminate\Http\Request;

class BannerSettingController extends Controller
{
    public function edit()
    {
        $settings = BannerSetting::getDefault();
        
        return view('admin.banner.edit', [
            'settings' => $settings,
            'positions' => ['bottom', 'top', 'center'],
            'layouts' => ['bar', 'box', 'popup'],
            'themes' => ['light', 'dark'],
            'buttonStyles' => ['filled', 'outlined'],
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'position' => 'required|in:bottom,top,center',
            'layout' => 'required|in:bar,box,popup',
            'theme' => 'required|in:light,dark',
            'primary_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'secondary_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'text_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'background_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'button_style' => 'required|in:filled,outlined',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'accept_button_text' => 'required|string|max:255',
            'reject_button_text' => 'required|string|max:255',
            'settings_button_text' => 'required|string|max:255',
            'show_reject_button' => 'boolean',
            'show_settings_button' => 'boolean',
            'z_index' => 'required|integer|min:0',
            'padding' => 'required|integer|min:0',
            'margin' => 'required|integer|min:0',
            'border_radius' => 'required|string|max:20',
            'font_family' => 'required|string|max:255',
            'font_size' => 'required|string|max:20',
            'is_active' => 'boolean',
        ]);

        $settings = BannerSetting::getDefault();
        $settings->update($validated);

        return redirect()->route('admin.banner.edit')
            ->with('success', 'Banner settings updated successfully.');
    }

    public function preview()
    {
        $settings = BannerSetting::getDefault();
        return view('admin.banner.preview', compact('settings'));
    }
}
