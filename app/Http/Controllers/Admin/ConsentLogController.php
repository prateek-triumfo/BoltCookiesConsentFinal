<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsentLog;
use App\Models\Domain;
use App\Models\ConsentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ConsentLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    { 
        $domains = Domain::all();
        $selectedDomain = null;
        $logs = null;

        if ($request->has('domain_id')) {
            $selectedDomain = Domain::find($request->domain_id);
            $logs = ConsentLog::where('domain_id', $request->domain_id)
                ->latest()
                ->paginate(15);
        }
        
        return view('admin.consent.logs.index', compact('domains', 'selectedDomain', 'logs'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ConsentLog $log)
    {
        // Get the consent categories to display proper names
        $categories = ConsentCategory::pluck('name', 'id')->toArray();
        
        return view('admin.consent.logs.show', compact('log', 'categories'));
    }

    /**
     * Export consent logs as CSV.
     */
    public function export()
    {
        $logs = ConsentLog::all();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="consent-logs-' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 
                'Cookie ID', 
                'Consent Data', 
                'IP Address', 
                'User Agent', 
                'Consented At'
            ]);
            
            // Add rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->cookie_id,
                    json_encode($log->consent_data),
                    $log->ip_address,
                    $log->user_agent,
                    $log->consented_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}