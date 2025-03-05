<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\BannerSetting;
use Illuminate\Http\Request;

class BannerSettingController extends Controller
{
    public function edit(Domain $domain)
    {
        $bannerSetting = $domain->bannerSetting ?? new BannerSetting(['domain_id' => $domain->id]);
        return view('admin.banner-settings.edit', compact('domain', 'bannerSetting'));
    }

    public function update(Request $request, Domain $domain)
    {
        $validated = $request->validate([
            'banner_title' => 'required|string|max:255',
            'banner_description' => 'required|string',
            'accept_all_text' => 'required|string|max:50',
            'reject_all_text' => 'required|string|max:50',
            'manage_settings_text' => 'required|string|max:50',
            'save_preferences_text' => 'required|string|max:50',
            'cancel_text' => 'required|string|max:50',
            'primary_color' => 'required|string|max:7',
            'accept_color' => 'required|string|max:7',
            'reject_color' => 'required|string|max:7',
            'background_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'font_family' => 'required|string',
            'font_size' => 'required|integer|min:10|max:24',
            'show_manage_button' => 'boolean',
            'manage_button_position' => 'required|in:bottom-right,bottom-left,top-right,top-left',
            'show_necessary_cookies' => 'boolean',
            'show_statistics_cookies' => 'boolean',
            'show_marketing_cookies' => 'boolean',
            'show_preferences_cookies' => 'boolean',
        ]);

        // Convert boolean fields
        $validated['show_manage_button'] = $request->boolean('show_manage_button');
        $validated['show_necessary_cookies'] = $request->boolean('show_necessary_cookies');
        $validated['show_statistics_cookies'] = $request->boolean('show_statistics_cookies');
        $validated['show_marketing_cookies'] = $request->boolean('show_marketing_cookies');
        $validated['show_preferences_cookies'] = $request->boolean('show_preferences_cookies');

        $bannerSetting = $domain->bannerSetting()->updateOrCreate(
            ['domain_id' => $domain->id],
            $validated
        );

        return redirect()->route('admin.banner.edit', $domain)
            ->with('success', 'Banner settings updated successfully.');
    }

    public function preview(Domain $domain)
    {
        $bannerSetting = $domain->bannerSetting ?? new BannerSetting(['domain_id' => $domain->id]);
        return view('admin.banner-settings.preview', compact('domain', 'bannerSetting'));
    }
}
