<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::query();

        // Filter by top-level only
        if ($request->boolean('top_level')) {
            $query->topLevel()->with('children');
        } else {
            // Include children relationships for all
            $query->with('children');
        }

        // Filter by parent_id
        if ($request->filled('parent_id')) {
            if ($request->parent_id === 'null') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent_id);
            }
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Determine if we need pagination
        if ($request->boolean('all')) {
            $categories = $query->orderBy('name')->get();
            return CategoryResource::collection($categories);
        }

        $categories = $query->orderBy('name')->paginate($request->get('per_page', 50));

        return CategoryResource::collection($categories);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category->load(['parent', 'children']));
    }
}
