<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ConsentLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        $logs = ConsentLog::latest()->paginate(15); 
        return view('admin.consent.logs.index', compact('logs'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ConsentLog $log)
    {
        return view('admin.consent.logs.show', compact('log'));
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