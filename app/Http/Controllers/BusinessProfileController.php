<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessProfileRequest;
use App\Http\Requests\UpdateBusinessProfileRequest;
use App\Models\BusinessProfile;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
            $query->where('is_verified', true);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('business_name', 'like', '%' . $request->search . '%');
        }

        // Filter by location (state)
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        $businesses = $query->paginate(15);

        return view('business-profiles.index', compact('businesses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user already has a business profile
        if (auth()->user()->businessProfile) {
            return redirect()->route('business-profiles.edit', auth()->user()->businessProfile)
                ->with('info', 'You already have a business profile. You can edit it here.');
        }

        $categories = Category::all();

        return view('business-profiles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBusinessProfileRequest $request)
    {
        // Check if user already has a business profile
        if (auth()->user()->businessProfile) {
            return redirect()->route('business-profiles.show', auth()->user()->businessProfile)
                ->with('error', 'You already have a business profile.');
        }

        $data = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('business-logos', 'public');
        }

        // Create business profile
        $businessProfile = auth()->user()->businessProfile()->create($data);

        // Attach categories
        if ($request->filled('categories')) {
            $businessProfile->categories()->sync($request->categories);
        }

        return redirect()->route('business-profiles.show', $businessProfile)
            ->with('success', 'Business profile created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BusinessProfile $businessProfile)
    {
        $businessProfile->load(['user', 'categories', 'offers.want']);

        return view('business-profiles.show', compact('businessProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusinessProfile $businessProfile)
    {
        Gate::authorize('update', $businessProfile);

        $categories = Category::all();

        return view('business-profiles.edit', compact('businessProfile', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBusinessProfileRequest $request, BusinessProfile $businessProfile)
    {
        $data = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($businessProfile->logo_path) {
                Storage::disk('public')->delete($businessProfile->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('business-logos', 'public');
        }

        $businessProfile->update($data);

        // Sync categories
        if ($request->filled('categories')) {
            $businessProfile->categories()->sync($request->categories);
        }

        return redirect()->route('business-profiles.show', $businessProfile)
            ->with('success', 'Business profile updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusinessProfile $businessProfile)
    {
        Gate::authorize('delete', $businessProfile);

        // Delete logo
        if ($businessProfile->logo_path) {
            Storage::disk('public')->delete($businessProfile->logo_path);
        }

        $businessProfile->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Business profile deleted successfully.');
    }
}
