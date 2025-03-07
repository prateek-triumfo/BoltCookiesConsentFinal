@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="card-header d-flex justify-content-between align-items-center p-6">
                <h5 class="mb-0">Banner Settings for {{ $domain->name }}</h5>
                <div>
                    <a href="{{ route('admin.banner-settings.preview', $domain) }}" class="btn btn-info btn-sm me-2" target="_blank">
                        <i class="fas fa-eye"></i> Preview
                    </a>
                    <a href="{{ route('admin.domains.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Domains
                    </a>
                </div>
            </div>

            <div class="p-6 bg-white border-b border-gray-200">
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
                        <div class="col-md-6">
                            <h6 class="mb-3">Basic Settings</h6>
                            
                            <div class="mb-3">
                                <label for="banner_title" class="form-label">Banner Title</label>
                                <input type="text" name="banner_title" id="banner_title" 
                                       class="form-control @error('banner_title') is-invalid @enderror" 
                                       value="{{ old('banner_title', $bannerSetting->banner_title) }}">
                                @error('banner_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="banner_description" class="form-label">Banner Description</label>
                                <textarea name="banner_description" id="banner_description" rows="3" 
                                          class="form-control @error('banner_description') is-invalid @enderror">{{ old('banner_description', $bannerSetting->banner_description) }}</textarea>
                                @error('banner_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="button_position" class="form-label">Button Position</label>
                                <select name="button_position" id="button_position" 
                                        class="form-control @error('button_position') is-invalid @enderror">
                                    <option value="left" {{ old('button_position', $bannerSetting->button_position) === 'left' ? 'selected' : '' }}>Left</option>
                                    <option value="right" {{ old('button_position', $bannerSetting->button_position) === 'right' ? 'selected' : '' }}>Right</option>
                                    <option value="center" {{ old('button_position', $bannerSetting->button_position) === 'center' ? 'selected' : '' }}>Center</option>
                                </select>
                                @error('button_position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Button Text Settings -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Button Text Settings</h6>
                            
                            <div class="mb-3">
                                <label for="accept_button_text" class="form-label">Accept Button Text</label>
                                <input type="text" name="accept_button_text" id="accept_button_text" 
                                       class="form-control @error('accept_button_text') is-invalid @enderror" 
                                       value="{{ old('accept_button_text', $bannerSetting->accept_button_text) }}">
                                @error('accept_button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="reject_button_text" class="form-label">Reject Button Text</label>
                                <input type="text" name="reject_button_text" id="reject_button_text" 
                                       class="form-control @error('reject_button_text') is-invalid @enderror" 
                                       value="{{ old('reject_button_text', $bannerSetting->reject_button_text) }}">
                                @error('reject_button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="manage_button_text" class="form-label">Manage Button Text</label>
                                <input type="text" name="manage_button_text" id="manage_button_text" 
                                       class="form-control @error('manage_button_text') is-invalid @enderror" 
                                       value="{{ old('manage_button_text', $bannerSetting->manage_button_text) }}">
                                @error('manage_button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="save_button_text" class="form-label">Save Button Text</label>
                                <input type="text" name="save_button_text" id="save_button_text" 
                                       class="form-control @error('save_button_text') is-invalid @enderror" 
                                       value="{{ old('save_button_text', $bannerSetting->save_button_text) }}">
                                @error('save_button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="cancel_button_text" class="form-label">Cancel Button Text</label>
                                <input type="text" name="cancel_button_text" id="cancel_button_text" 
                                       class="form-control @error('cancel_button_text') is-invalid @enderror" 
                                       value="{{ old('cancel_button_text', $bannerSetting->cancel_button_text) }}">
                                @error('cancel_button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Color Settings -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Color Settings</h6>
                            
                            <div class="mb-3">
                                <label for="primary_color" class="form-label">Primary Color</label>
                                <input type="color" name="primary_color" id="primary_color" 
                                       class="form-control form-control-color @error('primary_color') is-invalid @enderror" 
                                       value="{{ old('primary_color', $bannerSetting->primary_color) }}">
                                @error('primary_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="secondary_color" class="form-label">Secondary Color</label>
                                <input type="color" name="secondary_color" id="secondary_color" 
                                       class="form-control form-control-color @error('secondary_color') is-invalid @enderror" 
                                       value="{{ old('secondary_color', $bannerSetting->secondary_color) }}">
                                @error('secondary_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="text_color" class="form-label">Text Color</label>
                                <input type="color" name="text_color" id="text_color" 
                                       class="form-control form-control-color @error('text_color') is-invalid @enderror" 
                                       value="{{ old('text_color', $bannerSetting->text_color) }}">
                                @error('text_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="background_color" class="form-label">Background Color</label>
                                <input type="color" name="background_color" id="background_color" 
                                       class="form-control form-control-color @error('background_color') is-invalid @enderror" 
                                       value="{{ old('background_color', $bannerSetting->background_color) }}">
                                @error('background_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Font Settings -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Font Settings</h6>
                            
                            <div class="mb-3">
                                <label for="font_family" class="form-label">Font Family</label>
                                <input type="text" name="font_family" id="font_family" 
                                       class="form-control @error('font_family') is-invalid @enderror" 
                                       value="{{ old('font_family', $bannerSetting->font_family) }}">
                                @error('font_family')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="font_size" class="form-label">Font Size</label>
                                <input type="text" name="font_size" id="font_size" 
                                       class="form-control @error('font_size') is-invalid @enderror" 
                                       value="{{ old('font_size', $bannerSetting->font_size) }}">
                                @error('font_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Display Options -->
                        <div class="col-md-12">
                            <h6 class="mb-3">Display Options</h6>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="show_reject_button" id="show_reject_button" 
                                           class="form-check-input" value="1" 
                                           {{ old('show_reject_button', $bannerSetting->show_reject_button) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_reject_button">
                                        Show Reject Button
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="show_manage_button" id="show_manage_button" 
                                           class="form-check-input" value="1" 
                                           {{ old('show_manage_button', $bannerSetting->show_manage_button) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_manage_button">
                                        Show Manage Button
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="show_settings_button" id="show_settings_button" 
                                           class="form-check-input" value="1" 
                                           {{ old('show_settings_button', $bannerSetting->show_settings_button) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_settings_button">
                                        Show Settings Button
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 