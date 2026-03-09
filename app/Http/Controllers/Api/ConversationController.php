<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Models\Want;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource (user's conversations).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get conversations where user is either the consumer or the business
        $query = Conversation::with(['user', 'businessProfile', 'want', 'latestMessage'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);

                // Also include conversations for user's business
                if ($user->businessProfile) {
                    $q->orWhere('business_profile_id', $user->businessProfile->id);
                }
            })
            ->orderBy('last_message_at', 'desc');

        $conversations = $query->paginate($request->get('per_page', 15));

        return ConversationResource::collection($conversations);
    }

    /**
     * Store a newly created resource in storage (start a conversation).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'want_id' => ['required', 'exists:wants,id'],
            'business_profile_id' => ['required', 'exists:business_profiles,id'],
        ]);

        $want = Want::findOrFail($validated['want_id']);

        // Only want owner or business can create conversation
        $user = $request->user();
        $canCreate = $user->id === $want->user_id ||
                     ($user->businessProfile && $user->businessProfile->id == $validated['business_profile_id']);

        if (!$canCreate) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if conversation already exists
        $conversation = Conversation::where('want_id', $validated['want_id'])
            ->where('user_id', $want->user_id)
            ->where('business_profile_id', $validated['business_profile_id'])
            ->first();

        if ($conversation) {
            return new ConversationResource($conversation->load(['user', 'businessProfile', 'want']));
        }

        // Create new conversation
        $conversation = Conversation::create([
            'want_id' => $validated['want_id'],
            'user_id' => $want->user_id,
            'business_profile_id' => $validated['business_profile_id'],
        ]);

        return new ConversationResource($conversation->load(['user', 'businessProfile', 'want']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Conversation $conversation)
    {
        // Check if user is part of this conversation
        $user = $request->user();
        $isParticipant = $user->id === $conversation->user_id ||
                        ($user->businessProfile && $user->businessProfile->id === $conversation->business_profile_id);

        if (!$isParticipant) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new ConversationResource($conversation->load(['user', 'businessProfile', 'want', 'latestMessage']));
    }
}
