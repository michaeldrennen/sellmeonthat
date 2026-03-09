<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $want->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Want Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <a href="{{ route('wants.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                            &larr; Back to All Wants
                        </a>
                    </div>

                    <h1 class="text-4xl font-bold mb-2">{{ $want->title }}</h1>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Posted by: {{ $want->user->name }}</p>

                    <div class="prose max-w-none mb-6 dark:text-gray-300">
                        <p>{{ $want->description }}</p>
                    </div>

                    @if($want->budget_min || $want->budget_max)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                            <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                                Budget: ${{ number_format($want->budget_min, 2) }} - ${{ number_format($want->budget_max, 2) }}
                            </p>
                        </div>
                    @endif

                    @if($want->city || $want->state)
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <p>Location: {{ $want->city }}@if($want->city && $want->state), @endif{{ $want->state }}</p>
                        </div>
                    @endif

                    @if(auth()->check() && auth()->user()->businessProfile)
                        <div class="mt-6">
                            <form action="{{ route('conversations.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="want_id" value="{{ $want->id }}">
                                <input type="hidden" name="business_profile_id" value="{{ auth()->user()->businessProfile->id }}">
                                <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                                    Contact about this Want
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Offers Section -->
            @if($want->offers && $want->offers->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-2xl font-bold mb-4">Offers Received</h2>
                        <div class="space-y-4">
                            @foreach($want->offers as $offer)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-semibold">{{ $offer->businessProfile->business_name }}</h3>
                                            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">${{ number_format($offer->price, 2) }}</p>
                                            @if($offer->message)
                                                <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $offer->message }}</p>
                                            @endif
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                                Offered {{ $offer->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        @if(auth()->check() && auth()->id() === $want->user_id && $offer->status === 'pending')
                                            <form action="{{ route('offers.accept', $offer) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                                    Accept Offer
                                                </button>
                                            </form>
                                        @elseif($offer->status === 'accepted')
                                            <span class="px-4 py-2 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-lg font-semibold">
                                                Accepted
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons for Want Owner -->
            @if(auth()->check() && auth()->id() === $want->user_id)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex space-x-4">
                            <a href="{{ route('wants.edit', $want) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-md transition">
                                Edit Want
                            </a>
                            <form action="{{ route('wants.destroy', $want) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this want?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold shadow-md transition">
                                    Delete Want
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
