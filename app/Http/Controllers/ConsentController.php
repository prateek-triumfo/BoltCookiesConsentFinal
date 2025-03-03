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
            
        $bannerSettings = BannerSetting::getDefault();
            
        return view('consent.banner', compact('categories', 'bannerSettings'));
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
        
        // Get required categories and ensure they are always consented
        $requiredCategories = ConsentCategory::where('is_required', true)->get();
        foreach ($requiredCategories as $category) {
            $consentData[$category->key] = true;
        }

        // Detect device type using Agent
        $agent = new Agent();
        $deviceType = 'desktop';
        if ($agent->isRobot()) {
            $deviceType = 'bot';
        } elseif ($agent->isTablet()) {
            $deviceType = 'tablet';
        } elseif ($agent->isMobile()) {
            $deviceType = 'mobile';
        }

        // Get browser language
        $language = $request->getPreferredLanguage() ?? 'en';

        // Find existing consent log or create new one
        $consentLog = ConsentLog::where('cookie_id', $cookieId)->first();
        
        if ($consentLog) {
            // Update existing consent log
            $consentLog->update([
                'consent_data' => $consentData,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_type' => $deviceType,
                'language' => $language,
                'consented_at' => now(),
            ]);
        } else {
            // Create new consent log
            $consentLog = ConsentLog::create([
                'cookie_id' => $cookieId,
                'domain_id' => $domainModel->id,
                'consent_data' => $consentData,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_type' => $deviceType,
                'language' => $language,
                'consented_at' => now(),
            ]);
        }

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

        // Set cookie if it doesn't exist
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

        $cookieId = $request->cookie('consent_id');
        
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

                return response()->json([
                    'consented' => true,
                    'rejected' => false,
                    'preferences' => $preferences
                ]);
            }
        }

        return response()->json([
            'consented' => false,
            'rejected' => false,
            'preferences' => null
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
            
        $bannerSettings = BannerSetting::getDefault();
            
        return view('consent.manage', compact('categories', 'bannerSettings'));
    }
}