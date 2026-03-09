<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Models\Want;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Offer::with(['want', 'businessProfile']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by want
        if ($request->filled('want_id')) {
            $query->where('want_id', $request->want_id);
        }

        $offers = $query->latest()->paginate($request->get('per_page', 15));

        return OfferResource::collection($offers);
    }

    /**
     * Store a newly created resource in storage (for a specific want).
     */
    public function store(Request $request, Want $want)
    {
        // Check if user has business profile
        $businessProfile = $request->user()->businessProfile;

        if (!$businessProfile) {
            return response()->json([
                'message' => 'You must have a business profile to make offers.'
            ], 403);
        }

        // Check if want is open
        if ($want->status !== 'open') {
            return response()->json([
                'message' => 'This want is no longer accepting offers.'
            ], 422);
        }

        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $offer = $want->offers()->create([
            'business_profile_id' => $businessProfile->id,
            'price' => $validated['price'],
            'message' => $validated['message'] ?? null,
        ]);

        // Notify want owner about new offer
        $want->user->notify(new \App\Notifications\NewOfferNotification($offer));

        return new OfferResource($offer->load(['want', 'businessProfile']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Offer $offer)
    {
        return new OfferResource($offer->load(['want', 'businessProfile']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offer $offer)
    {
        // Only business that created offer can update
        if ($request->user()->businessProfile?->id !== $offer->business_profile_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Can't update accepted or rejected offers
        if (in_array($offer->status, ['accepted', 'rejected'])) {
            return response()->json([
                'message' => 'Cannot update an offer that has been accepted or rejected.'
            ], 422);
        }

        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $offer->update($validated);

        return new OfferResource($offer->load(['want', 'businessProfile']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        // Only business that created offer can delete
        if (auth()->user()->businessProfile?->id !== $offer->business_profile_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Can't delete accepted offers
        if ($offer->status === 'accepted') {
            return response()->json([
                'message' => 'Cannot delete an accepted offer.'
            ], 422);
        }

        $offer->delete();

        return response()->json(['message' => 'Offer deleted successfully'], 200);
    }

    /**
     * Accept an offer.
     */
    public function accept(Request $request, Offer $offer)
    {
        // Only want owner can accept
        if ($request->user()->id !== $offer->want->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Can't accept if want is closed
        if ($offer->want->status !== 'open') {
            return response()->json([
                'message' => 'This want is no longer open.'
            ], 422);
        }

        DB::transaction(function () use ($offer) {
            // Update the parent Want's status to 'closed'
            $offer->want->update(['status' => 'closed']);

            // Update the accepted Offer's status
            $offer->update(['status' => 'accepted']);

            // Reject all other offers for this Want
            $offer->want->offers()->where('id', '!=', $offer->id)->update(['status' => 'rejected']);
        });

        // Notify business owner about accepted offer
        $offer->businessProfile->user->notify(new \App\Notifications\OfferAcceptedNotification($offer));

        return new OfferResource($offer->load(['want', 'businessProfile']));
    }
}
