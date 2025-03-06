(function() {
    // Default configuration
    const defaultConfig = {
        scriptId: null,
        apiKey: null,
        apiUrl: window.location.origin + '/api/consent'
    };

    // Merge user config with defaults
    const config = {
        ...defaultConfig,
        ...(window.BOLT_CONSENT_CONFIG || {})
    };

    // Validate configuration
    if (!config.scriptId || !config.apiKey) {
        console.error('BoltConsent: Script ID and API Key are required. Please add them to your configuration like this:');
        console.error(`
            <script>
                window.BOLT_CONSENT_CONFIG = {
                    scriptId: 'your-domain-script-id',  // Get this from your BoltConsent dashboard
                    apiKey: 'your-domain-api-key'       // Get this from your BoltConsent dashboard
                };
            </script>
            <script src=window.location.origin + '/consent/embed.js'></script>
        `);
        return;
    }

    // Detect device type
    function getDeviceType() {
        const ua = navigator.userAgent;
        if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
            return 'tablet';
        }
        if (/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
            return 'mobile';
        }
        return 'desktop';
    }

    // Get browser language
    function getBrowserLanguage() {
        return navigator.language || navigator.userLanguage;
    }

    console.log('BoltConsent initialized with config:', config);

    // Create and inject the banner HTML
    function createBanner() {
        // Remove existing elements if they exist
        const existingBanner = document.getElementById('bolt-consent-banner');
        const existingManage = document.getElementById('bolt-consent-manage');
        if (existingBanner) existingBanner.remove();
        if (existingManage) existingManage.remove();

        const banner = document.createElement('div');
        banner.id = 'bolt-consent-banner';
        banner.innerHTML = `
            <div class="bolt-consent-banner" style="
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: #fff;
                padding: 20px;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
                z-index: 9999;
                display: flex;
                justify-content: space-between;
                align-items: center;
            ">
                <div style="flex: 1;">
                    <p style="margin: 0;">We use cookies to enhance your browsing experience and analyze our traffic.</p>
                </div>
                <div style="margin-left: 20px;">
                    <button id="bolt-reject-all" style="
                        background: #f44336;
                        color: white;
                        border: none;
                        padding: 8px 16px;
                        border-radius: 4px;
                        cursor: pointer;
                        margin-right: 10px;
                    ">Reject All</button>
                    <button id="bolt-accept-all" style="
                        background: #4CAF50;
                        color: white;
                        border: none;
                        padding: 8px 16px;
                        border-radius: 4px;
                        cursor: pointer;
                        margin-right: 10px;
                    ">Accept All</button>
                    <button id="bolt-manage-settings" style="
                        background: #2196F3;
                        color: white;
                        border: none;
                        padding: 8px 16px;
                        border-radius: 4px;
                        cursor: pointer;
                    ">Manage Settings</button>
                </div>
            </div>

            <!-- Settings Modal -->
            <div id="bolt-consent-settings" style="
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999999;
                overflow-y: auto;
                display: none;
                opacity: 0;
                transition: opacity 0.3s ease;
            ">
                <div style="
                    background: white;
                    max-width: 600px;
                    width: 90%;
                    margin: 40px auto;
                    padding: 30px;
                    border-radius: 8px;
                    position: relative;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    min-height: 200px;
                    transform: translateY(-20px);
                    transition: transform 0.3s ease;
                    z-index: 1000000;
                ">
                    <button id="bolt-close-settings" style="
                        position: absolute;
                        top: 10px;
                        right: 10px;
                        background: none;
                        border: none;
                        font-size: 24px;
                        cursor: pointer;
                        padding: 5px;
                        line-height: 1;
                        z-index: 1000001;
                    ">&times;</button>
                    
                    <h2 style="margin-top: 0; margin-right: 30px; position: relative; z-index: 1000001;">Cookie Preferences</h2>
                    
                    <div class="consent-categories" style="margin-top: 20px; position: relative; z-index: 1000001;">
                        <div class="consent-category">
                            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                <input type="checkbox" id="necessary" checked disabled style="margin-right: 10px;">
                                <label for="necessary" style="font-weight: bold;">Necessary Cookies</label>
                            </div>
                            <p style="margin: 0 0 20px 0; color: #666;">These cookies are essential for the website to function properly.</p>
                        </div>

                        <div class="consent-category">
                            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                <input type="checkbox" id="statistics" style="margin-right: 10px;">
                                <label for="statistics" style="font-weight: bold;">Statistics Cookies</label>
                            </div>
                            <p style="margin: 0 0 20px 0; color: #666;">These cookies help us understand how visitors interact with our website.</p>
                        </div>

                        <div class="consent-category">
                            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                <input type="checkbox" id="marketing" style="margin-right: 10px;">
                                <label for="marketing" style="font-weight: bold;">Marketing Cookies</label>
                            </div>
                            <p style="margin: 0 0 20px 0; color: #666;">These cookies are used to track visitors across websites to display relevant advertisements.</p>
                        </div>

                        <div class="consent-category">
                            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                <input type="checkbox" id="preferences" style="margin-right: 10px;">
                                <label for="preferences" style="font-weight: bold;">Preferences Cookies</label>
                            </div>
                            <p style="margin: 0 0 20px 0; color: #666;">These cookies allow the website to remember choices you make.</p>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                        <button id="bolt-cancel-settings" style="
                            background: #f5f5f5;
                            color: #333;
                            border: none;
                            padding: 8px 16px;
                            border-radius: 4px;
                            cursor: pointer;
                            font-weight: 500;
                        ">Cancel</button>
                        <button id="bolt-save-settings" style="
                            background: #4CAF50;
                            color: white;
                            border: none;
                            padding: 8px 16px;
                            border-radius: 4px;
                            cursor: pointer;
                            font-weight: 500;
                        ">Save Preferences</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(banner);

        // Create manage button separately
        const manageButton = document.createElement('div');
        manageButton.id = 'bolt-consent-manage';
        manageButton.innerHTML = `
            <button id="bolt-manage-cookies" style="
                background: #2196F3;
                color: white;
                border: none;
                padding: 8px 16px;
                border-radius: 4px;
                cursor: pointer;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                font-size: 14px;
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 9998;
            ">Manage Cookie Preferences</button>
        `;
        document.body.appendChild(manageButton);
    }

    // Function to show settings modal
    function showSettingsModal() {
        const modal = document.getElementById('bolt-consent-settings');
        if (modal) {
            modal.style.display = 'block';
            modal.style.opacity = '1';
            // Load current preferences
            const savedConsent = JSON.parse(localStorage.getItem('bolt_consent') || '{}');
            document.getElementById('statistics').checked = savedConsent.statistics || false;
            document.getElementById('marketing').checked = savedConsent.marketing || false;
            document.getElementById('preferences').checked = savedConsent.preferences || false;
        }
    }

    // Function to hide settings modal
    function hideSettingsModal() {
        const modal = document.getElementById('bolt-consent-settings');
        if (modal) {
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }

    // Function to check if GTM should be loaded
    function shouldLoadGTM() {
        const consentData = JSON.parse(localStorage.getItem('bolt_consent') || '{}');
        return consentData.marketing !== false && consentData.statistics !== false;
    }

    // Function to set GTM consent state
    function setGTMConsentState(consentData) {
        if (window.dataLayer) {
            window.dataLayer.push({
                'consent': {
                    'analytics_storage': consentData.statistics ? 'granted' : 'denied',
                    'ad_storage': consentData.marketing ? 'granted' : 'denied',
                    'personalization_storage': consentData.preferences ? 'granted' : 'denied',
                    'functionality_storage': consentData.preferences ? 'granted' : 'denied',
                    'security_storage': 'granted' // Always enabled for essential security
                }
            });
        }
    }

    // Function to validate and load GTM
    function validateAndLoadGTM() {
        const consentData = JSON.parse(localStorage.getItem('bolt_consent') || '{}');
        
        if (!shouldLoadGTM()) {
            // Set denied consent state
            setGTMConsentState(consentData);
            
            // Remove or disable GTM if consent is rejected
            if (window.dataLayer) {
                window.dataLayer.push({
                    'event': 'consent_rejected',
                    'consent_state': 'rejected',
                    'consent_data': consentData
                });
            }

            // Remove GTM script if it exists
            const gtmScript = document.querySelector('script[src*="googletagmanager.com/gtm.js"]');
            if (gtmScript) {
                gtmScript.remove();
            }

            // Remove GA4 script if it exists
            const gaScript = document.querySelector('script[src*="google-analytics.com/analytics.js"]');
            if (gaScript) {
                gaScript.remove();
            }

            // Remove Meta Pixel if it exists
            const metaScript = document.querySelector('script[src*="connect.facebook.net/signals/config/"]');
            if (metaScript) {
                metaScript.remove();
            }

            return false;
        } else {
            // Set granted consent state
            setGTMConsentState(consentData);
            
            // Push consent granted event
            if (window.dataLayer) {
                window.dataLayer.push({
                    'event': 'consent_granted',
                    'consent_state': 'granted',
                    'consent_data': consentData
                });
            }
            return true;
        }
    }

    // Function to set cookies based on consent
    function setConsentCookies(consentData) {
        // Set consent data cookie
        document.cookie = `bolt_consent=${JSON.stringify(consentData)};path=/;max-age=31536000;SameSite=Strict`;
        
        // Set individual category cookies
        Object.keys(consentData).forEach(category => {
            document.cookie = `bolt_consent_${category}=${consentData[category]};path=/;max-age=31536000;SameSite=Strict`;
        });

        // Set GTM consent cookies
        if (window.dataLayer) {
            window.dataLayer.push({
                'consent': {
                    'analytics_storage': consentData.statistics ? 'granted' : 'denied',
                    'ad_storage': consentData.marketing ? 'granted' : 'denied',
                    'personalization_storage': consentData.preferences ? 'granted' : 'denied',
                    'functionality_storage': consentData.preferences ? 'granted' : 'denied',
                    'security_storage': 'granted'
                }
            });
        }
    }

    // Function to initialize GTM with consent mode
    function initializeGTM() {
        // Initialize dataLayer with consent mode
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'consent': {
                'analytics_storage': 'denied',
                'ad_storage': 'denied',
                'personalization_storage': 'denied',
                'functionality_storage': 'denied',
                'security_storage': 'granted'
            }
        });
    }

    // Function to show banner
    function showBanner() {
        const banner = document.getElementById('bolt-consent-banner');
        const manageButton = document.getElementById('bolt-consent-manage');
        if (banner) {
            banner.style.display = 'flex';
        }
        if (manageButton) {
            manageButton.style.display = 'none';
        }
    }

    // Function to hide banner
    function hideBanner() {
        const banner = document.getElementById('bolt-consent-banner');
        const manageButton = document.getElementById('bolt-consent-manage');
        if (banner) {
            banner.style.display = 'none';
        }
        if (manageButton) {
            manageButton.style.display = 'block';
        }
    }

    // Save consent to the server
    async function saveConsent(consentData) {
        try {
            console.log('Saving consent with script ID:', config.scriptId);
            console.log('Consent data:', consentData);

            const response = await fetch(`${config.apiUrl}/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    script_id: config.scriptId,
                    api_key: config.apiKey,
                    consent_data: consentData,
                    domain: window.location.hostname,
                    ip_address: null,
                    user_agent: navigator.userAgent,
                    device_type: getDeviceType(),
                    language: getBrowserLanguage()
                })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Consent saved successfully:', data);
            
            // Store consent data in localStorage
            localStorage.setItem('bolt_consent', JSON.stringify(consentData));
            if (data.data && data.data.cookie_id) {
                localStorage.setItem('bolt_consent_cookie_id', data.data.cookie_id);
            }

            // Set cookies based on consent
            setConsentCookies(consentData);

            // Validate GTM after saving consent
            validateAndLoadGTM();

            // Hide banner and show manage button
            hideBanner();
            hideSettingsModal();

            return data;
        } catch (error) {
            console.error('Error saving consent:', error);
            alert('Failed to save consent preferences. Please try again.');
            throw error;
        }
    }

    // Initialize the consent system
    function init() {
        // Initialize GTM with consent mode
        initializeGTM();

        // Check if consent already exists
        const savedConsent = localStorage.getItem('bolt_consent');
        const savedCookieId = localStorage.getItem('bolt_consent_cookie_id');
        
        // Create elements
        createBanner();
        
        if (savedConsent && savedCookieId) {
            console.log('Consent already exists:', { savedConsent, savedCookieId });
            // Hide banner and show manage button
            hideBanner();
            // Validate GTM with saved consent
            validateAndLoadGTM();
        }

        // Add event listeners
        const rejectAllBtn = document.getElementById('bolt-reject-all');
        const acceptAllBtn = document.getElementById('bolt-accept-all');
        const manageSettingsBtn = document.getElementById('bolt-manage-settings');
        const manageCookiesBtn = document.getElementById('bolt-manage-cookies');
        const closeSettingsBtn = document.getElementById('bolt-close-settings');
        const cancelSettingsBtn = document.getElementById('bolt-cancel-settings');
        const saveSettingsBtn = document.getElementById('bolt-save-settings');

        // Reject All button
        if (rejectAllBtn) {
            rejectAllBtn.addEventListener('click', () => {
                const consentData = {
                    necessary: true,
                    statistics: false,
                    marketing: false,
                    preferences: false
                };
                saveConsent(consentData);
            });
        }

        // Accept All button
        if (acceptAllBtn) {
            acceptAllBtn.addEventListener('click', () => {
                const consentData = {
                    necessary: true,
                    statistics: true,
                    marketing: true,
                    preferences: true
                };
                saveConsent(consentData);
            });
        }

        // Manage Settings button
        if (manageSettingsBtn) {
            manageSettingsBtn.addEventListener('click', showSettingsModal);
        }

        // Manage Cookies button (floating button)
        if (manageCookiesBtn) {
            manageCookiesBtn.addEventListener('click', () => {
                showBanner();
                showSettingsModal();
            });
        }

        // Close Settings button
        if (closeSettingsBtn) {
            closeSettingsBtn.addEventListener('click', hideSettingsModal);
        }

        // Cancel Settings button
        if (cancelSettingsBtn) {
            cancelSettingsBtn.addEventListener('click', hideSettingsModal);
        }

        // Save Settings button
        if (saveSettingsBtn) {
            saveSettingsBtn.addEventListener('click', () => {
                const consentData = {
                    necessary: true,
                    statistics: document.getElementById('statistics').checked,
                    marketing: document.getElementById('marketing').checked,
                    preferences: document.getElementById('preferences').checked
                };
                saveConsent(consentData);
            });
        }
    }

    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Export functions for external use
    window.boltConsent = {
        validateAndLoadGTM,
        showBanner,
        hideBanner,
        saveConsent
    };
})();