<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WantResource;
use App\Models\Want;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Want::with(['user', 'categories'])
            ->published()
            ->open();

        // Search by title/description
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Filter by location (state)
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filter by budget range
        if ($request->filled('budget_min')) {
            $query->where('budget_max', '>=', $request->budget_min);
        }

        if ($request->filled('budget_max')) {
            $query->where('budget_min', '<=', $request->budget_max);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'published_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $wants = $query->paginate($request->get('per_page', 15));

        return WantResource::collection($wants);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'radius_miles' => ['nullable', 'integer', 'min:1', 'max:500'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_draft' => ['boolean'],
            'expires_at' => ['nullable', 'date', 'after:today'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('want-images', 'public');
            }
            $validated['image_paths'] = $imagePaths;
        }

        // Set published_at if not a draft
        $validated['is_draft'] = $validated['is_draft'] ?? false;
        if (!$validated['is_draft']) {
            $validated['published_at'] = now();
        }

        $want = $request->user()->wants()->create($validated);

        // Attach categories
        if (isset($validated['categories'])) {
            $want->categories()->sync($validated['categories']);
        }

        return new WantResource($want->load(['user', 'categories']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Want $want)
    {
        return new WantResource($want->load(['user', 'categories', 'offers.businessProfile']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Want $want)
    {
        $this->authorize('update', $want);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'radius_miles' => ['nullable', 'integer', 'min:1', 'max:500'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_draft' => ['boolean'],
            'expires_at' => ['nullable', 'date', 'after:today'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'delete_existing_images' => ['boolean'],
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            // Delete old images if requested
            if ($request->boolean('delete_existing_images') && $want->image_paths) {
                foreach ($want->image_paths as $path) {
                    Storage::disk('public')->delete($path);
                }
                $imagePaths = [];
            } else {
                $imagePaths = $want->image_paths ?? [];
            }

            // Add new images
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('want-images', 'public');
            }
            $validated['image_paths'] = $imagePaths;
        }

        // Set published_at if transitioning from draft to published
        if (isset($validated['is_draft']) && !$validated['is_draft'] && $want->is_draft) {
            $validated['published_at'] = now();
        }

        $want->update($validated);

        // Sync categories
        if (isset($validated['categories'])) {
            $want->categories()->sync($validated['categories']);
        }

        return new WantResource($want->load(['user', 'categories']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Want $want)
    {
        $this->authorize('delete', $want);

        // Delete images
        if ($want->image_paths) {
            foreach ($want->image_paths as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $want->delete();

        return response()->json(['message' => 'Want deleted successfully'], 200);
    }
}
