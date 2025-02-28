<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConsentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    { 
        // Skip for API routes and consent-related routes
        if ($request->is('api/*') || 
            $request->is('consent/*') || 
            $request->is('admin/consent/*')) {
            return $next($request);
        }

        // Add consent status to view data
        $consentCookieId = $request->cookie('consent_cookie_id');
        $consentPreferences = $request->cookie('consent_preferences');
        view()->share('hasConsent', !empty($consentCookieId) && !empty($consentPreferences));
        view()->share('consentPreferences', $consentPreferences ? json_decode($consentPreferences, true) : null);
        
        return $next($request);
    }
}