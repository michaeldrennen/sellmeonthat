<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Want;
use Illuminate\Http\Request;
use App\Http\Requests\StoreWantRequest;
use App\Http\Requests\UpdateWantRequest;
use Illuminate\Support\Facades\Gate;
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

        // Filter by budget range
        if ($request->filled('budget_min')) {
            $query->where('budget_max', '>=', $request->budget_min);
        }

        if ($request->filled('budget_max')) {
            $query->where('budget_min', '<=', $request->budget_max);
        }

        $wants = $query->latest('published_at')->paginate(15);
        $categories = Category::topLevel()->with('children')->get();

        return view('wants.index', compact('wants', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('wants.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWantRequest $request)
    {
        $data = $request->validated();

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('want-images', 'public');
            }
            $data['image_paths'] = $imagePaths;
        }

        // Set published_at if not a draft
        if (!$data['is_draft']) {
            $data['published_at'] = now();
        }

        $want = auth()->user()->wants()->create($data);

        // Attach categories
        if ($request->filled('categories')) {
            $want->categories()->sync($request->categories);
        }

        $message = $data['is_draft']
            ? 'Your want has been saved as a draft.'
            : 'Your want has been successfully posted!';

        return redirect()->route('wants.show', $want)->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Want $want)
    {
        $want->load(['user', 'categories', 'offers.businessProfile']);

        return view('wants.show', compact('want'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Want $want)
    {
        Gate::authorize('update', $want);

        $categories = Category::all();

        return view('wants.edit', compact('want', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWantRequest $request, Want $want)
    {
        $data = $request->validated();

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
            $data['image_paths'] = $imagePaths;
        }

        // Set published_at if transitioning from draft to published
        if (!$data['is_draft'] && $want->is_draft) {
            $data['published_at'] = now();
        }

        $want->update($data);

        // Sync categories
        if ($request->filled('categories')) {
            $want->categories()->sync($request->categories);
        }

        return redirect()->route('wants.show', $want)
            ->with('success', 'Your want has been successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Want $want)
    {
        Gate::authorize('delete', $want);

        // Delete images
        if ($want->image_paths) {
            foreach ($want->image_paths as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $want->delete();

        return redirect()->route('wants.index')
            ->with('success', 'Your want has been successfully deleted.');
    }
}
