@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Banner Settings</h5>
                    <div>
                        <a href="{{ route('admin.banner.preview') }}" class="btn btn-info btn-sm me-2" target="_blank">
                            Preview Banner
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.banner.update') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Layout Settings -->
                            <div class="col-md-6">
                                <h6 class="mb-3">Layout Settings</h6>
                                
                                <div class="mb-3">
                                    <label for="position" class="form-label">Banner Position</label>
                                    <select name="position" id="position" class="form-control @error('position') is-invalid @enderror">
                                        @foreach($positions as $pos)
                                            <option value="{{ $pos }}" {{ $settings->position === $pos ? 'selected' : '' }}>
                                                {{ ucfirst($pos) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="layout" class="form-label">Layout Style</label>
                                    <select name="layout" id="layout" class="form-control @error('layout') is-invalid @enderror">
                                        @foreach($layouts as $lay)
                                            <option value="{{ $lay }}" {{ $settings->layout === $lay ? 'selected' : '' }}>
                                                {{ ucfirst($lay) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('layout')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="theme" class="form-label">Theme</label>
                                    <select name="theme" id="theme" class="form-control @error('theme') is-invalid @enderror">
                                        @foreach($themes as $theme)
                                            <option value="{{ $theme }}" {{ $settings->theme === $theme ? 'selected' : '' }}>
                                                {{ ucfirst($theme) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('theme')
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
                                           value="{{ $settings->primary_color }}">
                                    @error('primary_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="secondary_color" class="form-label">Secondary Color</label>
                                    <input type="color" name="secondary_color" id="secondary_color" 
                                           class="form-control form-control-color @error('secondary_color') is-invalid @enderror" 
                                           value="{{ $settings->secondary_color }}">
                                    @error('secondary_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="text_color" class="form-label">Text Color</label>
                                    <input type="color" name="text_color" id="text_color" 
                                           class="form-control form-control-color @error('text_color') is-invalid @enderror" 
                                           value="{{ $settings->text_color }}">
                                    @error('text_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="background_color" class="form-label">Background Color</label>
                                    <input type="color" name="background_color" id="background_color" 
                                           class="form-control form-control-color @error('background_color') is-invalid @enderror" 
                                           value="{{ $settings->background_color }}">
                                    @error('background_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Content Settings -->
                            <div class="col-md-12 mt-4">
                                <h6 class="mb-3">Content Settings</h6>
                                
                                <div class="mb-3">
                                    <label for="title" class="form-label">Banner Title</label>
                                    <input type="text" name="title" id="title" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ $settings->title }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Banner Description</label>
                                    <textarea name="description" id="description" rows="3" 
                                              class="form-control @error('description') is-invalid @enderror">{{ $settings->description }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Button Settings -->
                            <div class="col-md-6">
                                <h6 class="mb-3">Button Settings</h6>
                                
                                <div class="mb-3">
                                    <label for="button_style" class="form-label">Button Style</label>
                                    <select name="button_style" id="button_style" class="form-control @error('button_style') is-invalid @enderror">
                                        @foreach($buttonStyles as $style)
                                            <option value="{{ $style }}" {{ $settings->button_style === $style ? 'selected' : '' }}>
                                                {{ ucfirst($style) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('button_style')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="accept_button_text" class="form-label">Accept Button Text</label>
                                    <input type="text" name="accept_button_text" id="accept_button_text" 
                                           class="form-control @error('accept_button_text') is-invalid @enderror" 
                                           value="{{ $settings->accept_button_text }}">
                                    @error('accept_button_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="reject_button_text" class="form-label">Reject Button Text</label>
                                    <input type="text" name="reject_button_text" id="reject_button_text" 
                                           class="form-control @error('reject_button_text') is-invalid @enderror" 
                                           value="{{ $settings->reject_button_text }}">
                                    @error('reject_button_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="settings_button_text" class="form-label">Settings Button Text</label>
                                    <input type="text" name="settings_button_text" id="settings_button_text" 
                                           class="form-control @error('settings_button_text') is-invalid @enderror" 
                                           value="{{ $settings->settings_button_text }}">
                                    @error('settings_button_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="show_reject_button" id="show_reject_button" 
                                               class="form-check-input" value="1" 
                                               {{ $settings->show_reject_button ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_reject_button">
                                            Show Reject Button
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="show_settings_button" id="show_settings_button" 
                                               class="form-check-input" value="1" 
                                               {{ $settings->show_settings_button ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_settings_button">
                                            Show Settings Button
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Style Settings -->
                            <div class="col-md-6">
                                <h6 class="mb-3">Style Settings</h6>
                                
                                <div class="mb-3">
                                    <label for="z_index" class="form-label">Z-Index</label>
                                    <input type="number" name="z_index" id="z_index" 
                                           class="form-control @error('z_index') is-invalid @enderror" 
                                           value="{{ $settings->z_index }}">
                                    @error('z_index')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="padding" class="form-label">Padding (px)</label>
                                    <input type="number" name="padding" id="padding" 
                                           class="form-control @error('padding') is-invalid @enderror" 
                                           value="{{ $settings->padding }}">
                                    @error('padding')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="margin" class="form-label">Margin (px)</label>
                                    <input type="number" name="margin" id="margin" 
                                           class="form-control @error('margin') is-invalid @enderror" 
                                           value="{{ $settings->margin }}">
                                    @error('margin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="border_radius" class="form-label">Border Radius</label>
                                    <input type="text" name="border_radius" id="border_radius" 
                                           class="form-control @error('border_radius') is-invalid @enderror" 
                                           value="{{ $settings->border_radius }}">
                                    @error('border_radius')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="font_family" class="form-label">Font Family</label>
                                    <input type="text" name="font_family" id="font_family" 
                                           class="form-control @error('font_family') is-invalid @enderror" 
                                           value="{{ $settings->font_family }}">
                                    @error('font_family')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="font_size" class="form-label">Font Size</label>
                                    <input type="text" name="font_size" id="font_size" 
                                           class="form-control @error('font_size') is-invalid @enderror" 
                                           value="{{ $settings->font_size }}">
                                    @error('font_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Active Status -->
                            <div class="col-md-12 mt-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" id="is_active" 
                                           class="form-check-input" value="1" 
                                           {{ $settings->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Banner Active
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 