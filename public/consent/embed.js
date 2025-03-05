(function() {
    // Default configuration
    const defaultConfig = {
        scriptId: null,
        apiKey: null,
        apiUrl: window.BOLT_CONSENT_CONFIG.apiUrl || 'http://cokkiesconsent.local/api/consent'
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
                    scriptId: 'your-script-id-here',
                    apiKey: 'your-api-key-here'
                };
            </script>
            <script src="http://cokkiesconsent.local/consent/embed.js"></script>
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
                    ip_address: null, // Will be set by the server
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
            
            // Store both the consent data and cookie_id in localStorage
            localStorage.setItem('bolt_consent', JSON.stringify(consentData));
            if (data.data && data.data.cookie_id) {
                localStorage.setItem('bolt_consent_cookie_id', data.data.cookie_id);
            }

            // Hide the banner and show manage button
            const banner = document.getElementById('bolt-consent-banner');
            const manageButton = document.getElementById('bolt-consent-manage');
            if (banner) {
                banner.style.display = 'none';
            }
            if (manageButton) {
                manageButton.style.display = 'block';
            }

            return data;
        } catch (error) {
            console.error('Error saving consent:', error);
            alert('Failed to save consent preferences. Please try again.');
            throw error;
        }
    }

    // Initialize the consent system
    function init() {
        // Check if consent already exists
        const savedConsent = localStorage.getItem('bolt_consent');
        const savedCookieId = localStorage.getItem('bolt_consent_cookie_id');
        
        // Create elements
        createBanner();
        
        if (savedConsent && savedCookieId) {
            console.log('Consent already exists:', { savedConsent, savedCookieId });
            // Hide banner and show manage button
            const banner = document.getElementById('bolt-consent-banner');
            const manageButton = document.getElementById('bolt-consent-manage');
            if (banner) {
                banner.style.display = 'none';
            }
            if (manageButton) {
                manageButton.style.display = 'block';
            }
        }

        // Expose methods to window
        window.boltConsent = {
            acceptAll: function() {
                saveConsent({
                    necessary: true,
                    statistics: true,
                    marketing: true,
                    preferences: true
                });
            },
            rejectAll: function() {
                saveConsent({
                    necessary: true, // Necessary cookies are always required
                    statistics: false,
                    marketing: false,
                    preferences: false
                });
            },
            showSettings: function() {
                console.log('showSettings called');
                const modal = document.getElementById('bolt-consent-settings');
                console.log('Modal element:', modal);
                if (modal) {
                    // First set display to block to ensure proper positioning
                    modal.style.display = 'block';
                    // Force a reflow
                    modal.offsetHeight;
                    // Then set flex properties for centering
                    modal.style.display = 'flex';
                    modal.style.alignItems = 'flex-start';
                    modal.style.justifyContent = 'center';
                    modal.style.paddingTop = '40px';
                    modal.style.opacity = '1';
                    modal.querySelector('div').style.transform = 'translateY(0)';
                    // Ensure modal is on top
                    modal.style.zIndex = '999999';
                    modal.querySelector('div').style.zIndex = '1000000';
                    console.log('Modal display set to flex');
                    // Set current preferences in the modal
                    const savedConsent = JSON.parse(localStorage.getItem('bolt_consent') || '{}');
                    console.log('Current saved consent:', savedConsent);
                    
                    const statisticsCheckbox = document.getElementById('statistics');
                    const marketingCheckbox = document.getElementById('marketing');
                    const preferencesCheckbox = document.getElementById('preferences');
                    
                    console.log('Checkbox elements:', {
                        statistics: statisticsCheckbox,
                        marketing: marketingCheckbox,
                        preferences: preferencesCheckbox
                    });
                    
                    if (statisticsCheckbox) statisticsCheckbox.checked = savedConsent.statistics || false;
                    if (marketingCheckbox) marketingCheckbox.checked = savedConsent.marketing || false;
                    if (preferencesCheckbox) preferencesCheckbox.checked = savedConsent.preferences || false;
                    
                    console.log('Checkbox states updated');
                } else {
                    console.error('Modal element not found');
                }
            },
            hideSettings: function() {
                console.log('hideSettings called');
                const modal = document.getElementById('bolt-consent-settings');
                if (modal) {
                    modal.style.opacity = '0';
                    modal.querySelector('div').style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        modal.style.display = 'none';
                        modal.style.alignItems = '';
                        modal.style.justifyContent = '';
                        modal.style.paddingTop = '';
                    }, 300);
                    console.log('Modal hidden');
                }
            },
            saveSettings: function() {
                console.log('saveSettings called');
                const consentData = {
                    necessary: true, // Necessary cookies are always required
                    statistics: document.getElementById('statistics').checked,
                    marketing: document.getElementById('marketing').checked,
                    preferences: document.getElementById('preferences').checked
                };
                console.log('New consent data:', consentData);
                saveConsent(consentData);
                this.hideSettings();
            }
        };

        // Attach event listeners after boltConsent is created
        console.log('Attaching event listeners');
        
        // Banner buttons
        const rejectAllBtn = document.getElementById('bolt-reject-all');
        const acceptAllBtn = document.getElementById('bolt-accept-all');
        const manageSettingsBtn = document.getElementById('bolt-manage-settings');
        const manageCookiesBtn = document.getElementById('bolt-manage-cookies');
        const closeSettingsBtn = document.getElementById('bolt-close-settings');
        const cancelSettingsBtn = document.getElementById('bolt-cancel-settings');
        const saveSettingsBtn = document.getElementById('bolt-save-settings');
        
        console.log('Button elements:', {
            rejectAll: rejectAllBtn,
            acceptAll: acceptAllBtn,
            manageSettings: manageSettingsBtn,
            manageCookies: manageCookiesBtn,
            closeSettings: closeSettingsBtn,
            cancelSettings: cancelSettingsBtn,
            saveSettings: saveSettingsBtn
        });
        
        // Function to safely add event listeners
        function addClickListener(element, callback, buttonName) {
            if (element) {
                element.addEventListener('click', (e) => {
                    console.log(`${buttonName} button clicked`);
                    e.preventDefault();
                    e.stopPropagation();
                    callback();
                });
                console.log(`Added click listener to ${buttonName} button`);
            } else {
                console.warn(`${buttonName} button not found`);
            }
        }
        
        // Add event listeners with debug logging
        addClickListener(rejectAllBtn, () => window.boltConsent.rejectAll(), 'Reject All');
        addClickListener(acceptAllBtn, () => window.boltConsent.acceptAll(), 'Accept All');
        addClickListener(manageSettingsBtn, () => window.boltConsent.showSettings(), 'Manage Settings');
        addClickListener(manageCookiesBtn, () => window.boltConsent.showSettings(), 'Manage Cookies');
        addClickListener(closeSettingsBtn, () => window.boltConsent.hideSettings(), 'Close Settings');
        addClickListener(cancelSettingsBtn, () => window.boltConsent.hideSettings(), 'Cancel Settings');
        addClickListener(saveSettingsBtn, () => window.boltConsent.saveSettings(), 'Save Settings');
        
        console.log('Event listeners attached');
    }

    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();