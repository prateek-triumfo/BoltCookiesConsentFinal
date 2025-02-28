<div id="consent-banner" class="consent-banner" style="display: none;">
    <div class="consent-banner-container">
        <div class="consent-banner-header">
            <h3>Cookie Consent</h3>
            <button type="button" class="consent-close" id="consent-close">&times;</button>
        </div>
        <div class="consent-banner-body">
            <p>We use cookies to enhance your browsing experience, serve personalized ads or content, and analyze our traffic. By clicking "Accept All", you consent to our use of cookies.</p>
            
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
            <button type="button" class="btn btn-outline-secondary" id="consent-reject-all">Reject All</button>
            <button type="button" class="btn btn-outline-primary" id="consent-preferences">Preferences</button>
            <button type="button" class="btn btn-primary" id="consent-accept-all">Accept All</button>
            <button type="button" class="btn btn-success" id="consent-save" style="display: none;">Save Preferences</button>
        </div>
    </div>
</div>

<style>
    .consent-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #fff;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        z-index: 9999;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .consent-banner-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
    }
    
    .consent-banner-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .consent-banner-header h3 {
        margin: 0;
        font-size: 1.25rem;
    }
    
    .consent-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }
    
    .consent-banner-body {
        margin-bottom: 1rem;
    }
    
    .consent-options {
        display: none;
        margin-top: 1rem;
    }
    
    .consent-option {
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }
    
    .consent-option:last-child {
        border-bottom: none;
    }
    
    .consent-description {
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .consent-required-badge {
        display: inline-block;
        background-color: #6c757d;
        color: white;
        font-size: 0.75rem;
        padding: 0.125rem 0.375rem;
        border-radius: 0.25rem;
        margin-left: 0.5rem;
    }
    
    .consent-banner-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }
    
    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        cursor: pointer;
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-outline-secondary:hover {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-outline-primary {
        color: #007bff;
        border-color: #007bff;
    }
    
    .btn-outline-primary:hover {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }
    
    .btn-success {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
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
    }
    
    .d-none {
        display: none !important;
    }
    
    @media (min-width: 768px) {
        .consent-banner-container {
            padding: 1.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const consentBanner = document.getElementById('consent-banner');
        const consentClose = document.getElementById('consent-close');
        const consentRejectAll = document.getElementById('consent-reject-all');
        const consentPreferences = document.getElementById('consent-preferences');
        const consentAcceptAll = document.getElementById('consent-accept-all');
        const consentSave = document.getElementById('consent-save');
        const consentOptions = document.querySelector('.consent-options');
        const consentCheckboxes = document.querySelectorAll('.consent-checkbox');
        
        // Check if consent has already been given
        fetch('{{ route('consent.preferences') }}')
            .then(response => response.json())
            .then(data => {
                if (!data.consented) {
                    consentBanner.style.display = 'block';
                } else {
                    // Apply saved preferences
                    applyConsentPreferences(data.preferences);
                }
            });
        
        // Close button (temporary hide)
        consentClose.addEventListener('click', function() {
            consentBanner.style.display = 'none';
        });
        
        // Reject All button
        consentRejectAll.addEventListener('click', function() {
            // Uncheck all non-required checkboxes
            consentCheckboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.checked = false;
                }
            });
            
            saveConsent();
        });
        
        // Preferences button
        consentPreferences.addEventListener('click', function() {
            consentOptions.style.display = 'block';
            consentAcceptAll.style.display = 'none';
            consentRejectAll.style.display = 'none';
            consentPreferences.style.display = 'none';
            consentSave.style.display = 'inline-block';
        });
        
        // Accept All button
        consentAcceptAll.addEventListener('click', function() {
            // Check all checkboxes
            consentCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            
            saveConsent();
        });
        
        // Save Preferences button
        consentSave.addEventListener('click', function() {
            saveConsent();
        });
        
        // Function to save consent
        function saveConsent() {
            const consentData = {};
            
            // Collect consent data
            consentCheckboxes.forEach(checkbox => {
                const key = checkbox.name.match(/\[(.*?)\]/)[1];
                consentData[key] = checkbox.checked ? true : false;
            });
            
            // Send consent data to server
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
                if (data.success) {
                    consentBanner.style.display = 'none';
                    applyConsentPreferences(consentData);
                }
            });
        }
        
        // Function to apply consent preferences
        function applyConsentPreferences(preferences) {
            // This function would enable/disable scripts based on consent
            // For example, only load Google Analytics if analytics consent is given
            if (preferences && preferences.analytics) {
                // Load analytics scripts
                console.log('Analytics scripts loaded');
            }
            
            if (preferences && preferences.marketing) {
                // Load marketing scripts
                console.log('Marketing scripts loaded');
            }
        }
    });
</script>