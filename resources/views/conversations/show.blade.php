<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Conversation with
                @if (auth()->user()->id === $conversation->user_id)
                    {{ $conversation->businessProfile->business_name }}
                @else
                    {{ $conversation->user->name }}
                @endif
            </h2>
            <a href="{{ route('conversations.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                &larr; Back to Conversations
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Want Context -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-1">About this Want:</h3>
                <a href="{{ route('wants.show', $conversation->want) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                    {{ $conversation->want->title }}
                </a>
            </div>

            <!-- Messages Container -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6">
                <div class="p-6 space-y-4 max-h-[600px] overflow-y-auto" id="messages-container">
                    @forelse ($messages->reverse() as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xl">
                                <div class="flex items-center mb-1 {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $message->sender->name }} • {{ $message->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div class="rounded-lg px-4 py-3 {{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }}">
                                    <p class="text-sm whitespace-pre-wrap">{{ $message->body }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Message Input Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <form action="{{ route('conversations.messages.store', $conversation) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Your Message
                        </label>
                        <textarea
                            name="body"
                            id="body"
                            rows="4"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Type your message here..."
                            required
                        >{{ old('body') }}</textarea>
                        @error('body')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition"
                        >
                            Send Message
                        </button>
                    </div>
                </form>
            </div>

            @if ($messages->hasPages())
                <div class="mt-6">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-scroll to bottom of messages on page load
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    </script>
    @endpush
</x-app-layout>
