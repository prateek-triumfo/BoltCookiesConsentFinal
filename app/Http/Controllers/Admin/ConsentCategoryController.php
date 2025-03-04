<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsentCategory;
use Illuminate\Http\Request;

class ConsentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ConsentCategory::orderBy('display_order')->get();
        return view('admin.consent.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        //return view('admin.consent.categories.create');
        $categories = ConsentCategory::orderBy('display_order')->get();
        return view('admin.consent.categories.create', compact('categories'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:consent_categories',
            'description' => 'required|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ]);

        ConsentCategory::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Consent category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConsentCategory $category) 
    { 
        
        $categories = ConsentCategory::orderBy('display_order')->get();
        return view('admin.consent.categories.edit', compact('categories','category'));
        //return view('admin.consent.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConsentCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:consent_categories,key,' . $category->id,
            'description' => 'required|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Consent category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConsentCategory $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Consent category deleted successfully.');
    }
}