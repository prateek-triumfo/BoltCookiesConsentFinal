<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index()
    { 
        $domains = Domain::all(); 
        return view('admin.consent.domains.index', compact('domains'));
    }

    public function create()
    {
        return view('admin.consent.domains.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:domains|max:255',
            'description' => 'nullable|string',
        ]);

        Domain::create($request->all());

        return redirect()->route('admin.consent.domains.index')
            ->with('success', 'Domain created successfully.');
    }

    public function edit(Domain $domain)
    {
        return view('admin.consent.domains.edit', compact('domain'));
    }

    public function update(Request $request, Domain $domain)
    {
        $request->validate([
            'name' => 'required|max:255|unique:domains,name,' . $domain->id,
            'description' => 'nullable|string',
        ]);

        $domain->update($request->all());

        return redirect()->route('admin.consent.domains.index')
            ->with('success', 'Domain updated successfully.');
    }

    public function destroy(Domain $domain)
    {
        $domain->delete();
        return redirect()->route('admin.consent.domains.index')
            ->with('success', 'Domain deleted successfully.');
    }
}
