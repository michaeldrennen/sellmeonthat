<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfferRequest;
use App\Models\Offer;
use App\Models\Want;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OfferController extends Controller
{


    public function accept(Request $request, Offer $offer)
    {
        // 1. Authorize the action using our OfferPolicy
        Gate::authorize('accept', $offer);

        DB::transaction(function () use ($offer) {
            // 2. Update the parent Want's status to 'closed'
            $offer->want->update(['status' => 'closed']);

            // 3. Update the accepted Offer's status
            $offer->update(['status' => 'accepted']);

            // 4. (Optional) Reject all other offers for this Want
            $offer->want->offers()->where('id', '!=', $offer->id)->update(['status' => 'rejected']);
        });

        return redirect()->route('wants.show', $offer->want)
                         ->with('success', 'Offer accepted! This want is now closed.');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferRequest $request, Want $want)
    {
        // Get the authenticated user's business profile
        $businessProfile = $request->user()->businessProfile;

        $want->offers()->create([
                                    'business_profile_id' => $businessProfile->id,
                                    'price' => $request->validated('price'),
                                    'message' => $request->validated('message'),
                                ]);

        return redirect()->route('wants.show', $want)
                         ->with('success', 'Your offer has been submitted!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
