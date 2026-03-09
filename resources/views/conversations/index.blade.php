<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Conversations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @forelse ($conversations as $conversation)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4 last:border-b-0">
                            <a href="{{ route('conversations.show', $conversation) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700 p-4 rounded-lg transition">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            @if (auth()->user()->id === $conversation->user_id)
                                                {{ $conversation->businessProfile->business_name }}
                                            @else
                                                {{ $conversation->user->name }}
                                            @endif
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            Re: {{ $conversation->want->title }}
                                        </p>
                                        @if ($conversation->latestMessage)
                                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
                                                {{ Str::limit($conversation->latestMessage->body, 100) }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right ml-4">
                                        @if ($conversation->last_message_at)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $conversation->last_message_at->diffForHumans() }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No conversations</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You don't have any conversations yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if ($conversations->hasPages())
                <div class="mt-6">
                    {{ $conversations->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
