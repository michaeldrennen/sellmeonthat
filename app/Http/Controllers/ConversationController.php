<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Want;
use App\Models\BusinessProfile;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's conversations.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $conversations = Conversation::with(['user', 'businessProfile', 'want', 'latestMessage.sender'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id);
                if ($user->businessProfile) {
                    $query->orWhere('business_profile_id', $user->businessProfile->id);
                }
            })
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        return view('conversations.index', compact('conversations'));
    }

    /**
     * Display the specified conversation with messages.
     */
    public function show(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        // Check if user is part of this conversation
        $isParticipant = $user->id === $conversation->user_id ||
                        ($user->businessProfile && $user->businessProfile->id === $conversation->business_profile_id);

        if (!$isParticipant) {
            abort(403, 'Unauthorized access to this conversation.');
        }

        // Load relationships
        $conversation->load(['user', 'businessProfile.user', 'want']);

        // Get messages with pagination
        $messages = $conversation->messages()
            ->with('sender')
            ->latest()
            ->paginate(50);

        // Mark unread messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('conversations.show', compact('conversation', 'messages'));
    }

    /**
     * Create or get conversation for a want and business.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'want_id' => ['required', 'exists:wants,id'],
            'business_profile_id' => ['required', 'exists:business_profiles,id'],
        ]);

        $user = $request->user();
        $want = Want::findOrFail($validated['want_id']);
        $businessProfile = BusinessProfile::findOrFail($validated['business_profile_id']);

        // Check authorization: user must be want owner or business owner
        $isWantOwner = $user->id === $want->user_id;
        $isBusinessOwner = $user->businessProfile && $user->businessProfile->id === $businessProfile->id;

        if (!$isWantOwner && !$isBusinessOwner) {
            abort(403, 'You are not authorized to create this conversation.');
        }

        // Find or create conversation
        $conversation = Conversation::firstOrCreate(
            [
                'want_id' => $want->id,
                'user_id' => $want->user_id,
                'business_profile_id' => $businessProfile->id,
            ],
            [
                'last_message_at' => now(),
            ]
        );

        return redirect()->route('conversations.show', $conversation);
    }
}
