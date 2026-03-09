<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $businessProfile->business_name }}
            </h2>
            @auth
                @if(auth()->user()->businessProfile && auth()->user()->businessProfile->id === $businessProfile->id)
                    <a href="{{ route('business-profiles.edit', $businessProfile) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-md transition">
                        Edit Profile
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Business Profile Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Logo -->
                        <div class="flex-shrink-0">
                            @if($businessProfile->logo_path)
                                <img src="{{ asset('storage/' . $businessProfile->logo_path) }}" alt="{{ $businessProfile->business_name }}" class="w-32 h-32 object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg shadow-md flex items-center justify-center">
                                    <span class="text-white text-5xl font-bold">{{ substr($businessProfile->business_name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Business Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $businessProfile->business_name }}
                                </h1>
                                @if($businessProfile->is_verified)
                                    <span class="px-3 py-1 text-sm bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified
                                    </span>
                                @endif
                            </div>

                            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $businessProfile->description }}</p>

                            <!-- Categories -->
                            @if($businessProfile->categories->isNotEmpty())
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($businessProfile->categories as $category)
                                        <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Contact Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                @if($businessProfile->phone)
                                    <div class="flex items-center text-gray-700 dark:text-gray-300">
                                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <a href="tel:{{ $businessProfile->phone }}" class="hover:text-blue-600 dark:hover:text-blue-400">{{ $businessProfile->phone }}</a>
                                    </div>
                                @endif

                                @if($businessProfile->website)
                                    <div class="flex items-center text-gray-700 dark:text-gray-300">
                                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                        </svg>
                                        <a href="{{ $businessProfile->website }}" target="_blank" class="hover:text-blue-600 dark:hover:text-blue-400">Visit Website</a>
                                    </div>
                                @endif

                                @if($businessProfile->city || $businessProfile->state)
                                    <div class="flex items-center text-gray-700 dark:text-gray-300">
                                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $businessProfile->city }}@if($businessProfile->city && $businessProfile->state), @endif{{ $businessProfile->state }}
                                    </div>
                                @endif

                                @if($businessProfile->service_radius_miles)
                                    <div class="flex items-center text-gray-700 dark:text-gray-300">
                                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                        Service radius: {{ $businessProfile->service_radius_miles }} miles
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Offers -->
            @if($businessProfile->offers && $businessProfile->offers->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Recent Offers</h2>
                        <div class="space-y-4">
                            @foreach($businessProfile->offers->take(10) as $offer)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                <a href="{{ route('wants.show', $offer->want) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $offer->want->title }}
                                                </a>
                                            </h3>
                                            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">
                                                ${{ number_format($offer->price, 2) }}
                                            </p>
                                            @if($offer->message)
                                                <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $offer->message }}</p>
                                            @endif
                                        </div>
                                        <span class="px-3 py-1 text-sm rounded-full
                                            {{ $offer->status === 'accepted' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                            {{ $offer->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                            {{ $offer->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}">
                                            {{ ucfirst($offer->status) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                        Offered {{ $offer->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
