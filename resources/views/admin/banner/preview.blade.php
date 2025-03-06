@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Banner Preview</h1>
                <a href="{{ route('admin.banner-settings.edit', $domain) }}" class="btn btn-primary btn-sm">Back to Settings</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <!-- Preview content will go here -->
                    <div class="preview-container">
                        <!-- Banner preview will be rendered here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Banner Preview -->
<div id="consent-banner-preview" style="
    position: fixed;
    {{ $settings->position === 'bottom' ? 'bottom: 0;' : '' }}
    {{ $settings->position === 'top' ? 'top: 0;' : '' }}
    {{ $settings->position === 'center' ? 'top: 50%; transform: translateY(-50%);' : '' }}
    left: 0;
    right: 0;
    background-color: {{ $settings->background_color }};
    color: {{ $settings->text_color }};
    padding: {{ $settings->padding }}px;
    margin: {{ $settings->margin }}px;
    z-index: {{ $settings->z_index }};
    border-radius: {{ $settings->border_radius }};
    font-family: {{ $settings->font_family }};
    font-size: {{ $settings->font_size }};
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
">
    <div class="container">
        <div class="{{ $settings->layout === 'box' ? 'col-md-6 mx-auto' : '' }}">
            <div class="{{ $settings->layout === 'popup' ? 'card' : '' }}">
                <div class="d-flex flex-column {{ $settings->layout !== 'bar' ? 'align-items-center text-center' : '' }}">
                    <h5 style="color: {{ $settings->text_color }};">{{ $settings->title }}</h5>
                    <p class="mb-3" style="color: {{ $settings->text_color }};">{{ $settings->description }}</p>
                    
                    <div class="d-flex {{ $settings->layout !== 'bar' ? 'flex-column' : '' }} gap-2">
                        <button class="btn {{ $settings->button_style === 'filled' ? 'btn-primary' : 'btn-outline-primary' }}"
                                style="background-color: {{ $settings->button_style === 'filled' ? $settings->primary_color : 'transparent' }};
                                       border-color: {{ $settings->primary_color }};
                                       color: {{ $settings->button_style === 'filled' ? '#fff' : $settings->primary_color }};">
                            {{ $settings->accept_button_text }}
                        </button>
                        
                        @if($settings->show_reject_button)
                        <button class="btn {{ $settings->button_style === 'filled' ? 'btn-secondary' : 'btn-outline-secondary' }}"
                                style="background-color: {{ $settings->button_style === 'filled' ? $settings->secondary_color : 'transparent' }};
                                       border-color: {{ $settings->secondary_color }};
                                       color: {{ $settings->button_style === 'filled' ? '#fff' : $settings->secondary_color }};">
                            {{ $settings->reject_button_text }}
                        </button>
                        @endif
                        
                        @if($settings->show_settings_button)
                        <button class="btn btn-link"
                                style="color: {{ $settings->primary_color }};">
                            {{ $settings->settings_button_text }}
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($settings->theme === 'dark')
<style>
    body {
        background-color: #333;
    }
    .card {
        background-color: #444;
        color: #fff;
    }
    .card-header {
        background-color: #555;
        border-bottom: 1px solid #666;
    }
    .text-muted {
        color: #aaa !important;
    }
</style>
@endif
@endsection 