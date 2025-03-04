<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DomainController extends Controller
{
    public function index()
    {
        try {
            $domains = Domain::latest()->paginate(10);
            Log::info('Domains data:', [
                'count' => $domains->count(),
                'total' => $domains->total(),
                'current_page' => $domains->currentPage(),
                'domains' => $domains->toArray()
            ]);
            
            return view('admin.domains.index', compact('domains'));
        } catch (\Exception $e) {
            Log::error('Error in DomainController@index:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function create()
    {
        return view('admin.domains.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:domains',
            'description' => 'nullable|string',
            'banner_settings' => 'nullable|array'
        ]);

        $domain = Domain::create([
            'name' => $request->name,
            'description' => $request->description,
            'api_key' => Str::random(32),
            'script_id' => Str::random(16),
            'banner_settings' => $request->banner_settings,
            'is_active' => true
        ]);

        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain created successfully.');
    }

    public function edit(Domain $domain)
    {
        return view('admin.domains.edit', compact('domain'));
    }

    public function update(Request $request, Domain $domain)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:domains,name,' . $domain->id,
            'description' => 'nullable|string',
            'banner_settings' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $domain->update([
            'name' => $request->name,
            'description' => $request->description,
            'banner_settings' => $request->banner_settings,
            'is_active' => $request->is_active ?? true
        ]);

        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain updated successfully.');
    }

    public function destroy(Domain $domain)
    {
        $domain->delete();
        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain deleted successfully.');
    }

    public function regenerateApiKey(Domain $domain)
    {
        $apiKey = $domain->generateApiKey();
        return redirect()->route('admin.domains.edit', $domain)
            ->with('success', 'API key regenerated successfully.');
    }

    public function regenerateScriptId(Domain $domain)
    {
        $scriptId = $domain->generateScriptId();
        return redirect()->route('admin.domains.edit', $domain)
            ->with('success', 'Script ID regenerated successfully.');
    }

    public function getEmbedCode(Domain $domain)
    {
        return view('admin.domains.embed-code', compact('domain'));
    }
}
