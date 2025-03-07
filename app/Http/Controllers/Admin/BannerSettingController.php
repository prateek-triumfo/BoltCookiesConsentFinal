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
        $bannerSetting = $domain->bannerSetting ?? new BannerSetting();
        return view('admin.banner-settings.edit', compact('domain', 'bannerSetting'));
    }

    public function update(Request $request, Domain $domain)
    {
        $validated = $request->validate([
            'banner_title' => 'required|string|max:255',
            'banner_description' => 'required|string',
            'accept_button_text' => 'required|string|max:50',
            'reject_button_text' => 'required|string|max:50',
            'manage_button_text' => 'required|string|max:50',
            'save_button_text' => 'required|string|max:50',
            'cancel_button_text' => 'required|string|max:50',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'background_color' => 'required|string|max:7',
            'font_family' => 'required|string|max:100',
            'font_size' => 'required|string|max:20',
            'show_reject_button' => 'boolean',
            'show_manage_button' => 'boolean',
            'show_settings_button' => 'boolean',
            'button_position' => 'required|in:left,right,center',
        ]);

        // Convert checkbox values to boolean
        $validated['show_reject_button'] = $request->has('show_reject_button');
        $validated['show_manage_button'] = $request->has('show_manage_button');
        $validated['show_settings_button'] = $request->has('show_settings_button');

        // Update or create banner settings
        $bannerSetting = $domain->bannerSetting()->updateOrCreate(
            ['domain_id' => $domain->id],
            $validated
        );

        return redirect()->route('admin.domains.index')
            ->with('success', 'Banner settings updated successfully.');
    }

    public function preview(Domain $domain)
    {
        $bannerSetting = $domain->bannerSetting;
        return view('admin.banner-settings.preview', compact('domain', 'bannerSetting'));
    }
}
