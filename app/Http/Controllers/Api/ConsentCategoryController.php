<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConsentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConsentCategoryController extends Controller
{
    public function index()
    {
        try {
            Log::info('Fetching consent categories');

            $categories = ConsentCategory::orderBy('display_order')->get()->map(function ($category) {
                return [
                    'key' => $category->key,
                    'name' => $category->name,
                    'description' => $category->description,
                    'is_required' => (bool)$category->is_required,
                    'order' => $category->display_order
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            Log::error('Error in ConsentCategoryController@index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while fetching consent categories',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 