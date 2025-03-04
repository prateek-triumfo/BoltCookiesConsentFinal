(function() {
    // Default configuration
    const defaultConfig = {
        scriptId: null,
        apiKey: null,
        apiUrl: 'http://cokkiesconsent.local/api/consent'
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

    console.log('BoltConsent initialized with config:', config);

    // Create and inject the banner HTML
    function createBanner() {
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
                    <button onclick="window.boltConsent.acceptAll()" style="
                        background: #4CAF50;
                        color: white;
                        border: none;
                        padding: 8px 16px;
                        border-radius: 4px;
                        cursor: pointer;
                        margin-right: 10px;
                    ">Accept All</button>
                    <button onclick="window.boltConsent.customize()" style="
                        background: #2196F3;
                        color: white;
                        border: none;
                        padding: 8px 16px;
                        border-radius: 4px;
                        cursor: pointer;
                    ">Customize</button>
                </div>
            </div>
        `;
        document.body.appendChild(banner);
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
                    user_agent: navigator.userAgent
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

            // Hide the banner
            const banner = document.getElementById('bolt-consent-banner');
            if (banner) {
                banner.style.display = 'none';
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
        
        if (savedConsent && savedCookieId) {
            console.log('Consent already exists:', { savedConsent, savedCookieId });
            return; // Don't show banner if consent exists
        }

        // Create and show the banner
        createBanner();

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
            customize: function() {
                // Show customization modal
                // This would be implemented based on your requirements
            }
        };
    }

    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})(); 