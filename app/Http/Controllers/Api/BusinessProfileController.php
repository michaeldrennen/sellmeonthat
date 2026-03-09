<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessProfileResource;
use App\Models\BusinessProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BusinessProfile::with(['user', 'categories']);

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Filter by verified status
        if ($request->filled('verified')) {
            $query->where('is_verified', $request->boolean('verified'));
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('business_name', 'like', '%' . $request->search . '%');
        }

        // Filter by location (state)
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $businesses = $query->paginate($request->get('per_page', 15));

        return BusinessProfileResource::collection($businesses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user already has a business profile
        if ($request->user()->businessProfile) {
            return response()->json([
                'message' => 'You already have a business profile.'
            ], 422);
        }

        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'website' => ['nullable', 'url', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'service_radius_miles' => ['nullable', 'integer', 'min:1', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('business-logos', 'public');
        }

        // Create business profile
        $businessProfile = $request->user()->businessProfile()->create($validated);

        // Attach categories
        if (isset($validated['categories'])) {
            $businessProfile->categories()->sync($validated['categories']);
        }

        return new BusinessProfileResource($businessProfile->load(['user', 'categories']));
    }

    /**
     * Display the specified resource.
     */
    public function show(BusinessProfile $businessProfile)
    {
        return new BusinessProfileResource($businessProfile->load(['user', 'categories', 'offers.want']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessProfile $businessProfile)
    {
        // Only owner can update
        if ($request->user()->id !== $businessProfile->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'website' => ['nullable', 'url', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'service_radius_miles' => ['nullable', 'integer', 'min:1', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($businessProfile->logo_path) {
                Storage::disk('public')->delete($businessProfile->logo_path);
            }

            $validated['logo_path'] = $request->file('logo')->store('business-logos', 'public');
        }

        $businessProfile->update($validated);

        // Sync categories
        if (isset($validated['categories'])) {
            $businessProfile->categories()->sync($validated['categories']);
        }

        return new BusinessProfileResource($businessProfile->load(['user', 'categories']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, BusinessProfile $businessProfile)
    {
        // Only owner can delete
        if ($request->user()->id !== $businessProfile->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete logo
        if ($businessProfile->logo_path) {
            Storage::disk('public')->delete($businessProfile->logo_path);
        }

        $businessProfile->delete();

        return response()->json(['message' => 'Business profile deleted successfully'], 200);
    }
}
