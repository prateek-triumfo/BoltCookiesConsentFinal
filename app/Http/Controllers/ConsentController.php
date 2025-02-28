<?php

namespace App\Http\Controllers;

use App\Models\ConsentCategory;
use App\Models\ConsentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class ConsentController extends Controller
{
    /**
     * Display the consent banner.
     */
    public function index()
    {
        $categories = ConsentCategory::where('is_active', true)
            ->orderBy('display_order')
            ->get();
            
        return view('consent.banner', compact('categories'));
    }

    /**
     * Save user consent preferences.
     */
    public function saveConsent(Request $request)
    {
        $validated = $request->validate([
            'consent' => 'required|array',
            'consent.*' => 'boolean',
        ]);

        // Ensure required categories are always consented
        $requiredCategories = ConsentCategory::where('is_required', true)
            ->pluck('key')
            ->toArray();

        foreach ($requiredCategories as $category) {
            $validated['consent'][$category] = true;
        }

        // Generate or retrieve cookie ID
        $cookieId = $request->cookie('consent_cookie_id');
        if (!$cookieId) {
            $cookieId = Str::uuid()->toString();
        }

        // Save consent log
        ConsentLog::updateOrCreate(
            ['cookie_id' => $cookieId],
            [
                'consent_data' => $validated['consent'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'consented_at' => now(),
            ]
        );

        // Set cookies
        $response = response()->json(['success' => true]);
        
        // Set consent cookie (1 year expiry)
        $response->cookie('consent_cookie_id', $cookieId, 60 * 24 * 365);
        $response->cookie('consent_preferences', json_encode($validated['consent']), 60 * 24 * 365);
        
        return $response;
    }

    /**
     * Get current consent preferences.
     */
    public function getConsent(Request $request)
    {
        $cookieId = $request->cookie('consent_cookie_id');
        $preferences = $request->cookie('consent_preferences');

        if ($cookieId && $preferences) {
            return response()->json([
                'consented' => true,
                'preferences' => json_decode($preferences, true),
            ]);
        }

        return response()->json([
            'consented' => false,
            'preferences' => null,
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
            
        return view('consent.manage', compact('categories'));
    }
}