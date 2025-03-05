<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\ConsentCategory;
use App\Models\ConsentLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $domain = Domain::find(1); // Replace with the appropriate logic to get the domain
        $domains = Domain::all();
        $categories = ConsentCategory::all();
        $consentLogs = ConsentLog::latest()->take(5)->get();

        return view('admin.dashboard', compact('domain', 'domains', 'categories', 'consentLogs'));
    }
}