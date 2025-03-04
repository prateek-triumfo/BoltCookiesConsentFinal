<?php

namespace App\Http\Controllers;

use App\Models\ConsentCategory;
use App\Models\ConsentLog;
use App\Models\BannerSetting;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class ConsentController extends Controller
{
    /**
     * Detect device type from user agent
     */
    private function detectDeviceType(Request $request)
    {
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());

        if ($agent->isRobot()) {
            return 'bot';
        } elseif ($agent->isTablet()) {
            return 'tablet';
        } elseif ($agent->isMobile()) {
            return 'mobile';
        } else {
            return 'desktop';
        }
    }

    /**
     * Get browser language preferences
     */
    private function getBrowserLanguage(Request $request)
    {
        $languages = $request->getLanguages();
        return !empty($languages) ? $languages[0] : 'en';
    }

    /**
     * Check if all non-required categories are accepted
     */
    private function isAllAccepted($consentData)
    {
        $categories = ConsentCategory::where('is_active', true)
            ->where('is_required', false)
            ->get();
            
        foreach ($categories as $category) {
            if (!isset($consentData[$category->key]) || !$consentData[$category->key]) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Initialize analytics and tracking platforms
     */
    private function initializeAnalytics($request)
    {
        // Example implementation - customize based on your needs
        $scriptTags = [];
        
        // Google Analytics 4
        if (config('services.google.analytics_id')) {
            $scriptTags[] = [
                'type' => 'script',
                'src' => 'https://www.googletagmanager.com/gtag/js?id=' . config('services.google.analytics_id'),
                'async' => true
            ];
            $scriptTags[] = [
                'type' => 'inline',
                'content' => "
                    window.dataLayer = window.dataLayer || [];
                    function gtag(){dataLayer.push(arguments);}
                    gtag('js', new Date());
                    gtag('config', '" . config('services.google.analytics_id') . "');
                "
            ];
        }
        
        // Facebook Pixel
        if (config('services.facebook.pixel_id')) {
            $scriptTags[] = [
                'type' => 'inline',
                'content' => "
                    !function(f,b,e,v,n,t,s)
                    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                    n.queue=[];t=b.createElement(e);t.async=!0;
                    t.src=v;s=b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t,s)}(window, document,'script',
                    'https://connect.facebook.net/en_US/fbevents.js');
                    fbq('init', '" . config('services.facebook.pixel_id') . "');
                    fbq('track', 'PageView');
                "
            ];
        }
        
        // Add more tracking platforms as needed
        
        return response()->json([
            'scripts' => $scriptTags
        ]);
    }

    /**
     * Display the consent banner.
     */
    public function index()
    {
        $categories = ConsentCategory::where('is_active', true)
            ->orderBy('display_order')
            ->get();
            
        $bannerSettings = BannerSetting::getDefaultSettings();
            
        return view('consent.banner', compact('categories', 'bannerSettings'));
    }

    /**
     * Set cookies based on consent categories
     */
    private function setCookiesByCategory($consentData)
    {
        $cookieOptions = [
            'expires' => time() + (365 * 24 * 60 * 60), // 1 year
            'path' => '/',
            'domain' => config('session.domain'),
            'secure' => config('session.secure'),
            'httponly' => true,
            'samesite' => config('session.same_site', 'lax')
        ];

        // Necessary cookies - always set regardless of consent
        Cookie::queue('necessary_cookies', '1', $cookieOptions['expires']);

        // Statistics cookies
        if (isset($consentData['statistics']) && $consentData['statistics']) {
            Cookie::queue('_ga', 'GA' . Str::random(10), $cookieOptions['expires']);
            Cookie::queue('_gid', 'GID' . Str::random(10), $cookieOptions['expires']);
        } else {
            Cookie::queue(Cookie::forget('_ga'));
            Cookie::queue(Cookie::forget('_gid'));
            Cookie::queue(Cookie::forget('_gat'));
        }

        // Marketing cookies
        if (isset($consentData['marketing']) && $consentData['marketing']) {
            Cookie::queue('_fbp', 'FB' . Str::random(10), $cookieOptions['expires']);
            if (config('services.facebook.pixel_id')) {
                Cookie::queue('_fbc', 'FBC' . Str::random(10), $cookieOptions['expires']);
            }
        } else {
            Cookie::queue(Cookie::forget('_fbp'));
            Cookie::queue(Cookie::forget('_fbc'));
        }

        // Preferences cookies
        if (isset($consentData['preferences']) && $consentData['preferences']) {
            Cookie::queue('user_preferences', json_encode([
                'theme' => request()->cookie('theme', 'light'),
                'language' => app()->getLocale(),
                'timezone' => request()->cookie('timezone', 'UTC')
            ]), $cookieOptions['expires']);
        } else {
            Cookie::queue(Cookie::forget('user_preferences'));
        }

        // Store the complete consent state
        Cookie::queue('consent_preferences', json_encode($consentData), $cookieOptions['expires']);
    }

    /**
     * Save user consent preferences.
     */
    public function saveConsent(Request $request)
    {
        // Validate request
        $request->validate([
            'consent' => 'required|array'
        ]);

        // Get or generate cookie ID
        $cookieId = $request->cookie('consent_cookie_id');
        if (!$cookieId) {
            $cookieId = 'consent_' . uniqid('', true);
        }

        // Get domain
        $domain = parse_url($request->root(), PHP_URL_HOST);
        if ($request->getPort() && !in_array($request->getPort(), [80, 443])) {
            $domain .= ':' . $request->getPort();
        }
        $domainModel = Domain::findOrCreateByName($domain);

        // Prepare consent data
        $consentData = $request->input('consent');
        
        // Ensure necessary cookies are always consented
        $consentData['necessary'] = true;
        
        // Set cookies based on consent categories
        $this->setCookiesByCategory($consentData);

        // Create new consent log
        $consentLog = ConsentLog::create([
            'cookie_id' => $cookieId,
            'domain_id' => $domainModel->id,
            'consent_data' => $consentData,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_type' => $this->detectDeviceType($request),
            'language' => $this->getBrowserLanguage($request),
            'consented_at' => now(),
        ]);

        // Check if all consents are accepted
        $allAccepted = $this->isAllAccepted($consentData);
        
        // Prepare response data
        $responseData = [
            'message' => 'Consent preferences saved successfully',
            'preferences' => $consentData,
        ];
        
        // If all consents are accepted, include analytics initialization scripts
        if ($allAccepted) {
            $analyticsResponse = $this->initializeAnalytics($request);
            $responseData['analytics'] = $analyticsResponse->original['scripts'];
        }

        // Prepare response
        $response = response()->json($responseData);

        // Set consent cookie ID if it doesn't exist
        if (!$request->cookie('consent_cookie_id')) {
            $response->cookie('consent_cookie_id', $cookieId, 365 * 24 * 60);
        }

        return $response;
    }

    /**
     * Get current consent preferences.
     */
    public function getConsent(Request $request)
    {
        // Check if user has rejected all cookies
        if ($request->cookie('consent_rejected')) {
            return response()->json([
                'consented' => false,
                'rejected' => true,
                'preferences' => null
            ]);
        }

        $cookieId = $request->cookie('consent_cookie_id'); // Fixed cookie name
        
        if ($cookieId) {
            $consentLog = ConsentLog::where('cookie_id', $cookieId)
                ->latest('consented_at')
                ->first();

            if ($consentLog) {
                // Get all active categories to ensure we have a complete set of preferences
                $activeCategories = ConsentCategory::where('is_active', true)->get();
                $preferences = [];
                
                // Initialize preferences with false for all categories
                foreach ($activeCategories as $category) {
                    $preferences[$category->key] = false;
                }
                
                // Update with saved preferences
                if ($consentLog->consent_data) {
                    foreach ($consentLog->consent_data as $key => $value) {
                        if (isset($preferences[$key])) {
                            $preferences[$key] = (bool)$value;
                        }
                    }
                }
                
                // Ensure required categories are always true
                $requiredCategories = $activeCategories->where('is_required', true);
                foreach ($requiredCategories as $category) {
                    $preferences[$category->key] = true;
                }

                // Check existing cookies and update preferences accordingly
                if (isset($preferences['statistics'])) {
                    $preferences['statistics'] = (bool)$request->cookie('_ga') || (bool)$request->cookie('_gid');
                }
                if (isset($preferences['marketing'])) {
                    $preferences['marketing'] = (bool)$request->cookie('_fbp') || (bool)$request->cookie('_fbc');
                }
                if (isset($preferences['preferences'])) {
                    $preferences['preferences'] = (bool)$request->cookie('user_preferences');
                }

                return response()->json([
                    'consented' => true,
                    'rejected' => false,
                    'preferences' => $preferences,
                    'cookie_id' => $cookieId
                ]);
            }
        }

        // If no consent found, return default preferences with required categories
        $activeCategories = ConsentCategory::where('is_active', true)->get();
        $defaultPreferences = [];
        
        foreach ($activeCategories as $category) {
            $defaultPreferences[$category->key] = $category->is_required;
        }

        return response()->json([
            'consented' => false,
            'rejected' => false,
            'preferences' => $defaultPreferences
        ]);
    }

    /**
     * Show the consent management page.
     */
    public function manage()
    {
        $categories = ConsentCategory::where('is_active', true)
            ->orderBy('display_order')
            ->get();
            
        $bannerSettings = BannerSetting::getDefaultSettings();
            
        return view('consent.manage', compact('categories', 'bannerSettings'));
    }
}