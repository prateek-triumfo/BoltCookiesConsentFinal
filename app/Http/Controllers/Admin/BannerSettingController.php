<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerSetting;
use Illuminate\Http\Request;

class BannerSettingController extends Controller
{
    public function edit()
    {
        $settings = BannerSetting::getDefaultSettings();
        
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'accept_all_text' => 'required|string|max:255',
            'customize_text' => 'required|string|max:255',
            'save_preferences_text' => 'required|string|max:255',
            'reject_all_text' => 'required|string|max:255',
            'cookie_categories' => 'required|array',
            'primary_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'secondary_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'background_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'text_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'is_active' => 'boolean',
        ]);

        $settings = BannerSetting::getDefaultSettings();
        $settings->update($validated);

        return redirect()->route('admin.banner.edit')
            ->with('success', 'Banner settings updated successfully.');
    }

    public function preview()
    {
        $settings = BannerSetting::getDefaultSettings();
        return view('admin.banner.preview', compact('settings'));
    }
}
