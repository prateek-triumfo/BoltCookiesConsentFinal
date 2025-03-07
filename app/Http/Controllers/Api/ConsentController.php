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
            Log::info('Saving consent data', $request->all());

            // Validate request
            $validated = $request->validate([
                'script_id' => 'required|string',
                'api_key' => 'required|string',
                'consent_data' => 'required|array',
                'domain' => 'required|string',
                'ip_address' => 'nullable|string',
                'user_agent' => 'required|string',
                'device_type' => 'required|string',
                'language' => 'required|string',
                'categories' => 'required|array'
            ]);

            // Find domain
            $domain = Domain::where('script_id', $validated['script_id'])
                          ->where('api_key', $validated['api_key'])
                          ->first();

            if (!$domain) {
                Log::warning('Invalid script ID or API key');
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid script ID or API key'
                ], 401);
            }

            // Generate a unique cookie_id
            $cookieId = 'consent_' . Str::random(32);

            // Get IP address from request if not provided
            $ipAddress = $validated['ip_address'] ?? $request->ip();

            // Create consent log
            $consentLog = ConsentLog::create([
                'domain_id' => $domain->id,
                'domain' => $validated['domain'],
                'cookie_id' => $cookieId,
                'ip_address' => $ipAddress,
                'user_agent' => $validated['user_agent'],
                'device_type' => $validated['device_type'],
                'language' => $validated['language'],
                'consent_data' => $validated['consent_data'],
                'categories' => $validated['categories'],
                'consented_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'cookie_id' => $cookieId,
                    'message' => 'Consent saved successfully'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in ConsentController@save: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while saving consent',
                'message' => $e->getMessage()
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