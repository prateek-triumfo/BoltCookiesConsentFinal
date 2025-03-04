<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\ConsentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ConsentController extends Controller
{
    public function saveConsent(Request $request)
    {
        try {
            Log::info('Received consent save request:', $request->all());

            // Validate the request
            $validated = $request->validate([
                'script_id' => 'required|string',
                'api_key' => 'required|string',
                'consent_data' => 'required|array',
                'ip_address' => 'nullable|ip',
                'user_agent' => 'nullable|string',
                'domain' => 'required|string',
                'device_type' => 'required|string|in:desktop,tablet,mobile',
                'language' => 'required|string'
            ]);

            Log::info('Request validated successfully');

            // Find the domain by script_id and api_key
            $domain = Domain::where('script_id', $validated['script_id'])
                          ->where('api_key', $validated['api_key'])
                          ->where('is_active', true)
                          ->first();

            if (!$domain) {
                Log::error('Domain not found or inactive:', [
                    'script_id' => $validated['script_id'],
                    'api_key' => $validated['api_key']
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid script ID or API key'
                ], 401);
            }

            // Generate a unique cookie_id
            $cookieId = 'consent_' . Str::random(32);

            // Create consent log
            $consentLog = ConsentLog::create([
                'domain_id' => $domain->id,
                'cookie_id' => $cookieId,
                'consent_data' => $validated['consent_data'],
                'ip_address' => $validated['ip_address'] ?? $request->ip(),
                'user_agent' => $validated['user_agent'] ?? $request->userAgent(),
                'domain' => $validated['domain'],
                'device_type' => $validated['device_type'],
                'language' => $validated['language'],
                'consented_at' => now()
            ]);

            Log::info('Consent saved successfully', [
                'consent_log_id' => $consentLog->id,
                'cookie_id' => $cookieId,
                'device_type' => $validated['device_type'],
                'language' => $validated['language']
            ]);

            // Increment domain's consent count
            $domain->incrementConsentCount();

            return response()->json([
                'success' => true,
                'message' => 'Consent saved successfully',
                'data' => [
                    'consent_log' => $consentLog,
                    'cookie_id' => $cookieId
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error saving consent:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save consent: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getConsent(Request $request)
    {
        try {
            Log::info('Received consent get request:', $request->all());

            // Validate the request
            $validated = $request->validate([
                'script_id' => 'required|string',
                'api_key' => 'required|string'
            ]);

            // Find the domain
            $domain = Domain::where('script_id', $validated['script_id'])
                          ->where('api_key', $validated['api_key'])
                          ->where('is_active', true)
                          ->first();

            if (!$domain) {
                Log::error('Domain not found or inactive:', [
                    'script_id' => $validated['script_id'],
                    'api_key' => $validated['api_key']
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid script ID or API key'
                ], 401);
            }

            // Get the latest consent log for this domain
            $consentLog = ConsentLog::where('domain_id', $domain->id)
                                  ->latest()
                                  ->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'consent_data' => $consentLog ? $consentLog->consent_data : null,
                    'cookie_id' => $consentLog ? $consentLog->cookie_id : null,
                    'device_type' => $consentLog ? $consentLog->device_type : null,
                    'language' => $consentLog ? $consentLog->language : null
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting consent:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get consent: ' . $e->getMessage()
            ], 500);
        }
    }
} 