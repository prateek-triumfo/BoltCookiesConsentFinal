<div id="consent-banner" class="consent-banner" style="display: none;">
    <div class="consent-banner-container">
        <div class="consent-banner-header">
            <h3>{{ $bannerSettings->title }}</h3>
            <button type="button" class="consent-close" id="consent-close">&times;</button>
        </div>
        <div class="consent-banner-body">
            <p>{{ $bannerSettings->description }}</p>
            
            <div class="consent-options">
                @foreach($categories as $category)
                    <div class="consent-option">
                        <div class="form-check">
                            <input class="form-check-input consent-checkbox" 
                                   type="checkbox" 
                                   value="1" 
                                   id="consent-{{ $category->key }}" 
                                   name="consent[{{ $category->key }}]" 
                                   {{ $category->is_required ? 'checked disabled' : '' }}>
                            <label class="form-check-label" for="consent-{{ $category->key }}">
                                <strong>{{ $category->name }}</strong>
                                <span class="consent-required-badge {{ $category->is_required ? '' : 'd-none' }}">Required</span>
                            </label>
                        </div>
                        <p class="consent-description">{{ $category->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="consent-banner-footer">
            @if($bannerSettings->show_reject_button)
            <button type="button" class="btn btn-outline-secondary" id="consent-reject-all">{{ $bannerSettings->reject_button_text }}</button>
            @endif
            @if($bannerSettings->show_settings_button)
            <button type="button" class="btn btn-outline-primary" id="consent-preferences">{{ $bannerSettings->settings_button_text }}</button>
            @endif
            <button type="button" class="btn btn-primary" id="consent-accept-all">{{ $bannerSettings->accept_button_text }}</button>
            <button type="button" class="btn btn-success" id="consent-save" style="display: none;">Save Preferences</button>
        </div>
    </div>
</div>

<div class="consent-banner-manage" style="display: none;">
    @if($bannerSettings->show_settings_button)
    <button type="button" class="btn btn-outline-primary" id="consent-preference-manage">
        {{ $bannerSettings->settings_button_text }}
    </button>
    @endif
</div>

<style>
/* Style for the 'Manage Preferences' button */
.consent-banner-manage {
    position: fixed;
    bottom: {{ $bannerSettings->margin }}px;
    right: {{ $bannerSettings->margin }}px;
    z-index: {{ $bannerSettings->z_index - 1 }};
}

#consent-preference-manage {
    font-size: {{ $bannerSettings->font_size }};
    padding: 0.75rem 1.5rem;
    background-color: {{ $bannerSettings->button_style === 'filled' ? $bannerSettings->primary_color : 'transparent' }};
    color: {{ $bannerSettings->button_style === 'filled' ? '#fff' : $bannerSettings->primary_color }};
    border: 2px solid {{ $bannerSettings->primary_color }};
    border-radius: {{ $bannerSettings->border_radius }};
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s, border-color 0.3s, transform 0.2s;
}

#consent-preference-manage:hover {
    background-color: {{ $bannerSettings->button_style === 'filled' ? $bannerSettings->secondary_color : $bannerSettings->primary_color }};
    border-color: {{ $bannerSettings->button_style === 'filled' ? $bannerSettings->secondary_color : $bannerSettings->primary_color }};
    color: #fff;
    transform: translateY(-2px);
}

#consent-preference-manage:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(38, 143, 255, 0.5);
}

.consent-banner {
    position: fixed;
    {{ $bannerSettings->position === 'bottom' ? 'bottom: 0;' : '' }}
    {{ $bannerSettings->position === 'top' ? 'top: 0;' : '' }}
    {{ $bannerSettings->position === 'center' ? 'top: 50%; transform: translateY(-50%);' : '' }}
    left: 0;
    right: 0;
    background-color: {{ $bannerSettings->background_color }};
    color: {{ $bannerSettings->text_color }};
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    z-index: {{ $bannerSettings->z_index }};
    font-family: {{ $bannerSettings->font_family }};
    font-size: {{ $bannerSettings->font_size }};
}

.consent-banner-container {
    @if($bannerSettings->layout === 'box')
    max-width: 600px;
    @elseif($bannerSettings->layout === 'popup')
    max-width: 500px;
    @else
    max-width: 1200px;
    @endif
    margin: 0 auto;
    padding: {{ $bannerSettings->padding }}px;
}

.consent-banner-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid {{ $bannerSettings->text_color }}20;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.consent-banner-header h3 {
    margin: 0;
    font-size: calc({{ $bannerSettings->font_size }} * 1.2);
    color: {{ $bannerSettings->text_color }};
}

.consent-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    color: {{ $bannerSettings->text_color }};
}

.consent-banner-body {
    margin-bottom: 1rem;
    color: {{ $bannerSettings->text_color }};
}

.consent-options {
    display: none;
    margin-top: 1rem;
}

.consent-option {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid {{ $bannerSettings->text_color }}20;
}

.consent-option:last-child {
    border-bottom: none;
}

.consent-description {
    margin-top: 0.25rem;
    font-size: calc({{ $bannerSettings->font_size }} * 0.9);
    color: {{ $bannerSettings->text_color }}CC;
}

.consent-required-badge {
    display: inline-block;
    background-color: {{ $bannerSettings->secondary_color }};
    color: #fff;
    font-size: calc({{ $bannerSettings->font_size }} * 0.75);
    padding: 0.125rem 0.375rem;
    border-radius: {{ $bannerSettings->border_radius }};
    margin-left: 0.5rem;
}

.consent-banner-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.btn {
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: {{ $bannerSettings->font_size }};
    line-height: 1.5;
    border-radius: {{ $bannerSettings->border_radius }};
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    cursor: pointer;
}

.btn-outline-secondary {
    color: {{ $bannerSettings->secondary_color }};
    border-color: {{ $bannerSettings->secondary_color }};
}

.btn-outline-secondary:hover {
    color: #fff;
    background-color: {{ $bannerSettings->secondary_color }};
    border-color: {{ $bannerSettings->secondary_color }};
}

.btn-outline-primary {
    color: {{ $bannerSettings->primary_color }};
    border-color: {{ $bannerSettings->primary_color }};
}

.btn-outline-primary:hover {
    color: #fff;
    background-color: {{ $bannerSettings->primary_color }};
    border-color: {{ $bannerSettings->primary_color }};
}

.btn-primary {
    color: #fff;
    background-color: {{ $bannerSettings->primary_color }};
    border-color: {{ $bannerSettings->primary_color }};
}

.btn-primary:hover {
    background-color: {{ $bannerSettings->secondary_color }};
    border-color: {{ $bannerSettings->secondary_color }};
}

.btn-success {
    color: #fff;
    background-color: {{ $bannerSettings->primary_color }};
    border-color: {{ $bannerSettings->primary_color }};
}

.btn-success:hover {
    background-color: {{ $bannerSettings->secondary_color }};
    border-color: {{ $bannerSettings->secondary_color }};
}

.form-check {
    position: relative;
    display: block;
    padding-left: 1.25rem;
}

.form-check-input {
    position: absolute;
    margin-top: 0.3rem;
    margin-left: -1.25rem;
}

.form-check-label {
    margin-bottom: 0;
    color: {{ $bannerSettings->text_color }};
}

@if($bannerSettings->theme === 'dark')
body {
    background-color: #333;
}
.consent-banner {
    background-color: #444;
}
.consent-banner-header {
    border-bottom-color: #666;
}
.consent-option {
    border-bottom-color: #666;
}
@endif

@media (min-width: 768px) {
    .consent-banner-manage {
        bottom: {{ $bannerSettings->margin + 10 }}px;
        right: {{ $bannerSettings->margin + 10 }}px;
    }
    #consent-preference-manage {
        font-size: calc({{ $bannerSettings->font_size }} * 1.1);
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const consentBanner = document.getElementById('consent-banner');
        const consentBannerManage = document.querySelector('.consent-banner-manage');
        const consentClose = document.getElementById('consent-close');
        const consentRejectAll = document.getElementById('consent-reject-all');
        const consentPreferences = document.getElementById('consent-preferences');
        const consentAcceptAll = document.getElementById('consent-accept-all');
        const consentSave = document.getElementById('consent-save');
        const consentOptions = document.querySelector('.consent-options');
        const consentCheckboxes = document.querySelectorAll('.consent-checkbox');
        const consentPreferenceManage = document.getElementById('consent-preference-manage');
        
        // Function to initialize analytics scripts
        function initializeAnalytics(scripts) {
            scripts.forEach(script => {
                if (script.type === 'script') {
                    // External script
                    const scriptElement = document.createElement('script');
                    scriptElement.src = script.src;
                    scriptElement.async = script.async || false;
                    document.head.appendChild(scriptElement);
                } else if (script.type === 'inline') {
                    // Inline script
                    const scriptElement = document.createElement('script');
                    scriptElement.textContent = script.content;
                    document.head.appendChild(scriptElement);
                }
            });
        }
        
        // Function to toggle between banner modes (simple/detailed)
        function toggleBannerMode(showPreferences) {
            if (consentOptions) {
                consentOptions.style.display = showPreferences ? 'block' : 'none';
            }
            if (consentSave) {
                consentSave.style.display = showPreferences ? 'block' : 'none';
            }
            if (consentAcceptAll) {
                consentAcceptAll.style.display = showPreferences ? 'none' : 'block';
            }
            if (consentRejectAll) {
                consentRejectAll.style.display = showPreferences ? 'none' : 'block';
            }
            if (consentPreferences) {
                consentPreferences.style.display = showPreferences ? 'none' : 'block';
            }
        }
        
        // Function to update checkboxes based on preferences
        function updateCheckboxes(preferences) {
            if (!preferences) return;
            
            consentCheckboxes.forEach(checkbox => {
                const key = checkbox.name.match(/\[(.*?)\]/)[1];
                if (!checkbox.disabled && preferences[key] !== undefined) {
                    checkbox.checked = preferences[key];
                }
            });
        }
        
        // Function to update banner visibility
        function updateBannerVisibility(show, showPreferences = false) {
            if (consentBanner) {
                consentBanner.style.display = show ? 'block' : 'none';
            }
            if (consentBannerManage) {
                consentBannerManage.style.display = show ? 'none' : 'block';
            }
            if (show && showPreferences) {
                toggleBannerMode(true);
            } else if (show) {
                toggleBannerMode(false);
            }
        }
        
        // Check if consent has already been given
        fetch('{{ route('consent.preferences') }}')
            .then(response => response.json())
            .then(data => {
                if (!data.consented) {
                    updateBannerVisibility(true);
                } else {
                    updateBannerVisibility(false);
                    updateCheckboxes(data.preferences);
                }
            })
            .catch(error => {
                console.error('Error loading preferences:', error);
            });
        
        // Close button (temporary hide)
        if (consentClose) {
            consentClose.addEventListener('click', function() {
                updateBannerVisibility(false);
            });
        }
        
        // Reject All button
        if (consentRejectAll) {
            consentRejectAll.addEventListener('click', function() {
                consentCheckboxes.forEach(checkbox => {
                    if (!checkbox.disabled) {
                        checkbox.checked = false;
                    }
                });
                saveConsent();
            });
        }
        
        // Preferences button
        if (consentPreferences) {
            consentPreferences.addEventListener('click', function() {
                toggleBannerMode(true);
            });
        }
        
        // Manage Preferences button (floating button)
        if (consentPreferenceManage) {
            consentPreferenceManage.addEventListener('click', function() {
                updateBannerVisibility(true, true);
            });
        }
        
        // Accept All button
        if (consentAcceptAll) {
            consentAcceptAll.addEventListener('click', function() {
                consentCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                saveConsent();
            });
        }
        
        // Save Preferences button
        if (consentSave) {
            consentSave.addEventListener('click', function() {
                saveConsent();
            });
        }
        
        // Function to save consent
        function saveConsent() {
            const consentData = {};
            
            consentCheckboxes.forEach(checkbox => {
                const key = checkbox.name.match(/\[(.*?)\]/)[1];
                consentData[key] = checkbox.checked;
            });
            
            fetch('{{ route('consent.save') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ consent: consentData })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Consent preferences saved successfully') {
                    updateBannerVisibility(false);
                    updateCheckboxes(data.preferences);
                    
                    // Initialize analytics if scripts are provided
                    if (data.analytics && Array.isArray(data.analytics)) {
                        initializeAnalytics(data.analytics);
                    }
                }
            })
            .catch(error => {
                console.error('Error saving preferences:', error);
            });
        }
    });
</script>