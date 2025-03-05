@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Preview Banner for {{ $domain->name }}</h5>
                    <div>
                        <a href="{{ route('admin.banner-settings.edit', $domain) }}" class="btn btn-primary btn-sm me-2">
                            Edit Settings
                        </a>
                        <a href="{{ route('admin.domains.index') }}" class="btn btn-secondary btn-sm">
                            Back to Domains
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Banner Preview -->
                    <div id="cookie-consent-banner" style="
                        font-family: {{ $bannerSetting->font_family }};
                        font-size: {{ $bannerSetting->font_size }};
                        background-color: white;
                        border: 1px solid #e5e7eb;
                        border-radius: 8px;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                        max-width: 400px;
                        margin: 20px auto;
                        padding: 20px;
                        position: relative;
                    ">
                        <div style="margin-bottom: 16px;">
                            <h3 style="
                                color: {{ $bannerSetting->text_color }};
                                font-size: 1.25em;
                                font-weight: 600;
                                margin: 0 0 8px 0;
                            ">{{ $bannerSetting->banner_title }}</h3>
                            <p style="
                                color: {{ $bannerSetting->text_color }};
                                margin: 0;
                                line-height: 1.5;
                            ">{{ $bannerSetting->banner_description }}</p>
                        </div>

                        <div style="display: flex; gap: 8px; margin-bottom: 16px;">
                            <button style="
                                background-color: {{ $bannerSetting->accept_color }};
                                color: white;
                                border: none;
                                padding: 8px 16px;
                                border-radius: 4px;
                                cursor: pointer;
                                font-weight: 500;
                            ">{{ $bannerSetting->accept_all_text }}</button>

                            @if($bannerSetting->show_reject_button)
                            <button style="
                                background-color: {{ $bannerSetting->reject_color }};
                                color: white;
                                border: none;
                                padding: 8px 16px;
                                border-radius: 4px;
                                cursor: pointer;
                                font-weight: 500;
                            ">{{ $bannerSetting->reject_all_text }}</button>
                            @endif

                            @if($bannerSetting->show_settings_button)
                            <button onclick="document.getElementById('cookie-settings-modal').style.display='block'" style="
                                background-color: {{ $bannerSetting->manage_color }};
                                color: white;
                                border: none;
                                padding: 8px 16px;
                                border-radius: 4px;
                                cursor: pointer;
                                font-weight: 500;
                                {{ $bannerSetting->manage_button_position === 'right' ? 'margin-left: auto;' : '' }}
                            ">{{ $bannerSetting->manage_settings_text }}</button>
                            @endif
                        </div>
                    </div>

                    <!-- Cookie Settings Modal -->
                    <div id="cookie-settings-modal" style="
                        display: none;
                        font-family: {{ $bannerSetting->font_family }};
                        font-size: {{ $bannerSetting->font_size }};
                        background-color: white;
                        border: 1px solid #e5e7eb;
                        border-radius: 8px;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                        max-width: 600px;
                        margin: 20px auto;
                        padding: 24px;
                    ">
                        <h3 style="
                            color: {{ $bannerSetting->text_color }};
                            font-size: 1.25em;
                            font-weight: 600;
                            margin: 0 0 16px 0;
                        ">Cookie Settings</h3>

                        @if($bannerSetting->show_categories_menu)
                        <div style="margin-bottom: 24px;">
                            <!-- Necessary Cookies -->
                            <div style="margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                    <input type="checkbox" checked disabled style="margin-right: 8px;">
                                    <label style="
                                        color: {{ $bannerSetting->text_color }};
                                        font-weight: 500;
                                    ">{{ $bannerSetting->necessary_cookie_title }}</label>
                                </div>
                                <p style="
                                    color: {{ $bannerSetting->text_color }};
                                    margin: 0;
                                    padding-left: 24px;
                                ">{{ $bannerSetting->necessary_cookie_description }}</p>
                            </div>

                            @if($bannerSetting->show_statistics_category)
                            <!-- Statistics Cookies -->
                            <div style="margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                    <input type="checkbox" style="margin-right: 8px;">
                                    <label style="
                                        color: {{ $bannerSetting->text_color }};
                                        font-weight: 500;
                                    ">{{ $bannerSetting->statistics_cookie_title }}</label>
                                </div>
                                <p style="
                                    color: {{ $bannerSetting->text_color }};
                                    margin: 0;
                                    padding-left: 24px;
                                ">{{ $bannerSetting->statistics_cookie_description }}</p>
                            </div>
                            @endif

                            @if($bannerSetting->show_marketing_category)
                            <!-- Marketing Cookies -->
                            <div style="margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                    <input type="checkbox" style="margin-right: 8px;">
                                    <label style="
                                        color: {{ $bannerSetting->text_color }};
                                        font-weight: 500;
                                    ">{{ $bannerSetting->marketing_cookie_title }}</label>
                                </div>
                                <p style="
                                    color: {{ $bannerSetting->text_color }};
                                    margin: 0;
                                    padding-left: 24px;
                                ">{{ $bannerSetting->marketing_cookie_description }}</p>
                            </div>
                            @endif

                            @if($bannerSetting->show_preferences_category)
                            <!-- Preferences Cookies -->
                            <div style="margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                    <input type="checkbox" style="margin-right: 8px;">
                                    <label style="
                                        color: {{ $bannerSetting->text_color }};
                                        font-weight: 500;
                                    ">{{ $bannerSetting->preferences_cookie_title }}</label>
                                </div>
                                <p style="
                                    color: {{ $bannerSetting->text_color }};
                                    margin: 0;
                                    padding-left: 24px;
                                ">{{ $bannerSetting->preferences_cookie_description }}</p>
                            </div>
                            @endif
                        </div>
                        @endif

                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            <button onclick="document.getElementById('cookie-settings-modal').style.display='none'" style="
                                background-color: {{ $bannerSetting->cancel_color }};
                                color: white;
                                border: none;
                                padding: 8px 16px;
                                border-radius: 4px;
                                cursor: pointer;
                                font-weight: 500;
                            ">{{ $bannerSetting->cancel_text }}</button>
                            <button style="
                                background-color: {{ $bannerSetting->save_color }};
                                color: white;
                                border: none;
                                padding: 8px 16px;
                                border-radius: 4px;
                                cursor: pointer;
                                font-weight: 500;
                            ">{{ $bannerSetting->save_preferences_text }}</button>
                        </div>
                    </div>

                    <!-- Preview Controls -->
                    <div class="text-center mt-4">
                        <button onclick="document.getElementById('cookie-settings-modal').style.display='none'" class="btn btn-secondary me-2">Close Settings</button>
                        <button onclick="document.getElementById('cookie-settings-modal').style.display='block'" class="btn btn-primary">Open Settings</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 