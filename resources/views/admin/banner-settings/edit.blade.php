@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Banner Settings for {{ $domain->name }}</h5>
                    <div>
                        <a href="{{ route('admin.banner-settings.preview', $domain) }}" class="btn btn-info btn-sm me-2" target="_blank">
                            Preview Banner
                        </a>
                        <a href="{{ route('admin.domains.index') }}" class="btn btn-secondary btn-sm">
                            Back to Domains
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.banner-settings.update', $domain) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Basic Settings -->
                            <div class="col-md-6 mb-4">
                                <h6 class="mb-3">Basic Settings</h6>
                                
                                <div class="mb-3">
                                    <label for="banner_title" class="form-label">Banner Title</label>
                                    <input type="text" class="form-control @error('banner_title') is-invalid @enderror" 
                                           id="banner_title" name="banner_title" 
                                           value="{{ old('banner_title', $bannerSetting->banner_title) }}">
                                    @error('banner_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="banner_description" class="form-label">Banner Description</label>
                                    <textarea class="form-control @error('banner_description') is-invalid @enderror" 
                                              id="banner_description" name="banner_description" rows="3">{{ old('banner_description', $bannerSetting->banner_description) }}</textarea>
                                    @error('banner_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Color Settings -->
                            <div class="col-md-6 mb-4">
                                <h6 class="mb-3">Color Settings</h6>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="primary_color" class="form-label">Primary Color</label>
                                        <input type="color" class="form-control form-control-color w-100" 
                                               id="primary_color" name="primary_color" 
                                               value="{{ old('primary_color', $bannerSetting->primary_color) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="text_color" class="form-label">Text Color</label>
                                        <input type="color" class="form-control form-control-color w-100" 
                                               id="text_color" name="text_color" 
                                               value="{{ old('text_color', $bannerSetting->text_color) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="accept_color" class="form-label">Accept Button Color</label>
                                        <input type="color" class="form-control form-control-color w-100" 
                                               id="accept_color" name="accept_color" 
                                               value="{{ old('accept_color', $bannerSetting->accept_color) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="reject_color" class="form-label">Reject Button Color</label>
                                        <input type="color" class="form-control form-control-color w-100" 
                                               id="reject_color" name="reject_color" 
                                               value="{{ old('reject_color', $bannerSetting->reject_color) }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="manage_color" class="form-label">Manage Button Color</label>
                                        <input type="color" class="form-control form-control-color w-100" 
                                               id="manage_color" name="manage_color" 
                                               value="{{ old('manage_color', $bannerSetting->manage_color) }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="save_color" class="form-label">Save Button Color</label>
                                        <input type="color" class="form-control form-control-color w-100" 
                                               id="save_color" name="save_color" 
                                               value="{{ old('save_color', $bannerSetting->save_color) }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="cancel_color" class="form-label">Cancel Button Color</label>
                                        <input type="color" class="form-control form-control-color w-100" 
                                               id="cancel_color" name="cancel_color" 
                                               value="{{ old('cancel_color', $bannerSetting->cancel_color) }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Font Settings -->
                            <div class="col-md-6 mb-4">
                                <h6 class="mb-3">Font Settings</h6>
                                
                                <div class="mb-3">
                                    <label for="font_family" class="form-label">Font Family</label>
                                    <select class="form-select" id="font_family" name="font_family">
                                        <option value="Arial" {{ old('font_family', $bannerSetting->font_family) == 'Arial' ? 'selected' : '' }}>Arial</option>
                                        <option value="Helvetica" {{ old('font_family', $bannerSetting->font_family) == 'Helvetica' ? 'selected' : '' }}>Helvetica</option>
                                        <option value="Times New Roman" {{ old('font_family', $bannerSetting->font_family) == 'Times New Roman' ? 'selected' : '' }}>Times New Roman</option>
                                        <option value="Georgia" {{ old('font_family', $bannerSetting->font_family) == 'Georgia' ? 'selected' : '' }}>Georgia</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="font_size" class="form-label">Font Size</label>
                                    <select class="form-select" id="font_size" name="font_size">
                                        <option value="12px" {{ old('font_size', $bannerSetting->font_size) == '12px' ? 'selected' : '' }}>12px</option>
                                        <option value="14px" {{ old('font_size', $bannerSetting->font_size) == '14px' ? 'selected' : '' }}>14px</option>
                                        <option value="16px" {{ old('font_size', $bannerSetting->font_size) == '16px' ? 'selected' : '' }}>16px</option>
                                        <option value="18px" {{ old('font_size', $bannerSetting->font_size) == '18px' ? 'selected' : '' }}>18px</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Button Text Settings -->
                            <div class="col-md-6 mb-4">
                                <h6 class="mb-3">Button Text Settings</h6>
                                
                                <div class="mb-3">
                                    <label for="accept_all_text" class="form-label">Accept Button Text</label>
                                    <input type="text" class="form-control" id="accept_all_text" name="accept_all_text" 
                                           value="{{ old('accept_all_text', $bannerSetting->accept_all_text) }}">
                                </div>

                                <div class="mb-3">
                                    <label for="reject_all_text" class="form-label">Reject Button Text</label>
                                    <input type="text" class="form-control" id="reject_all_text" name="reject_all_text" 
                                           value="{{ old('reject_all_text', $bannerSetting->reject_all_text) }}">
                                </div>

                                <div class="mb-3">
                                    <label for="manage_settings_text" class="form-label">Manage Settings Text</label>
                                    <input type="text" class="form-control" id="manage_settings_text" name="manage_settings_text" 
                                           value="{{ old('manage_settings_text', $bannerSetting->manage_settings_text) }}">
                                </div>

                                <div class="mb-3">
                                    <label for="save_preferences_text" class="form-label">Save Preferences Text</label>
                                    <input type="text" class="form-control" id="save_preferences_text" name="save_preferences_text" 
                                           value="{{ old('save_preferences_text', $bannerSetting->save_preferences_text) }}">
                                </div>

                                <div class="mb-3">
                                    <label for="cancel_text" class="form-label">Cancel Text</label>
                                    <input type="text" class="form-control" id="cancel_text" name="cancel_text" 
                                           value="{{ old('cancel_text', $bannerSetting->cancel_text) }}">
                                </div>
                            </div>

                            <!-- Cookie Categories -->
                            <div class="col-md-12 mb-4">
                                <h6 class="mb-3">Cookie Categories</h6>
                                
                                <div class="row">
                                    <!-- Necessary Cookies -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>Necessary Cookies</h6>
                                                <div class="mb-3">
                                                    <label for="necessary_cookie_title" class="form-label">Title</label>
                                                    <input type="text" class="form-control" id="necessary_cookie_title" 
                                                           name="necessary_cookie_title" 
                                                           value="{{ old('necessary_cookie_title', $bannerSetting->necessary_cookie_title) }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="necessary_cookie_description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="necessary_cookie_description" 
                                                              name="necessary_cookie_description" 
                                                              rows="2">{{ old('necessary_cookie_description', $bannerSetting->necessary_cookie_description) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Statistics Cookies -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>Statistics Cookies</h6>
                                                <div class="mb-3">
                                                    <label for="statistics_cookie_title" class="form-label">Title</label>
                                                    <input type="text" class="form-control" id="statistics_cookie_title" 
                                                           name="statistics_cookie_title" 
                                                           value="{{ old('statistics_cookie_title', $bannerSetting->statistics_cookie_title) }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="statistics_cookie_description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="statistics_cookie_description" 
                                                              name="statistics_cookie_description" 
                                                              rows="2">{{ old('statistics_cookie_description', $bannerSetting->statistics_cookie_description) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Marketing Cookies -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>Marketing Cookies</h6>
                                                <div class="mb-3">
                                                    <label for="marketing_cookie_title" class="form-label">Title</label>
                                                    <input type="text" class="form-control" id="marketing_cookie_title" 
                                                           name="marketing_cookie_title" 
                                                           value="{{ old('marketing_cookie_title', $bannerSetting->marketing_cookie_title) }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="marketing_cookie_description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="marketing_cookie_description" 
                                                              name="marketing_cookie_description" 
                                                              rows="2">{{ old('marketing_cookie_description', $bannerSetting->marketing_cookie_description) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preferences Cookies -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>Preferences Cookies</h6>
                                                <div class="mb-3">
                                                    <label for="preferences_cookie_title" class="form-label">Title</label>
                                                    <input type="text" class="form-control" id="preferences_cookie_title" 
                                                           name="preferences_cookie_title" 
                                                           value="{{ old('preferences_cookie_title', $bannerSetting->preferences_cookie_title) }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="preferences_cookie_description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="preferences_cookie_description" 
                                                              name="preferences_cookie_description" 
                                                              rows="2">{{ old('preferences_cookie_description', $bannerSetting->preferences_cookie_description) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Display Options -->
                            <div class="col-md-6 mb-4">
                                <h6 class="mb-3">Display Options</h6>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="show_reject_button" 
                                               name="show_reject_button" value="1" 
                                               {{ old('show_reject_button', $bannerSetting->show_reject_button) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_reject_button">Show Reject Button</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="show_settings_button" 
                                               name="show_settings_button" value="1" 
                                               {{ old('show_settings_button', $bannerSetting->show_settings_button) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_settings_button">Show Settings Button</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="show_categories_menu" 
                                               name="show_categories_menu" value="1" 
                                               {{ old('show_categories_menu', $bannerSetting->show_categories_menu) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_categories_menu">Show Categories Menu</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="show_statistics_category" 
                                               name="show_statistics_category" value="1" 
                                               {{ old('show_statistics_category', $bannerSetting->show_statistics_category) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_statistics_category">Show Statistics Category</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="show_marketing_category" 
                                               name="show_marketing_category" value="1" 
                                               {{ old('show_marketing_category', $bannerSetting->show_marketing_category) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_marketing_category">Show Marketing Category</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="show_preferences_category" 
                                               name="show_preferences_category" value="1" 
                                               {{ old('show_preferences_category', $bannerSetting->show_preferences_category) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_preferences_category">Show Preferences Category</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="manage_button_position" class="form-label">Manage Button Position</label>
                                    <select class="form-select" id="manage_button_position" name="manage_button_position">
                                        <option value="left" {{ old('manage_button_position', $bannerSetting->manage_button_position) == 'left' ? 'selected' : '' }}>Left</option>
                                        <option value="right" {{ old('manage_button_position', $bannerSetting->manage_button_position) == 'right' ? 'selected' : '' }}>Right</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 