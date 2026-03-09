<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Wants') }}
            </h2>
            @auth
                <a href="{{ route('wants.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-md transition">
                    Post a Want
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($wants as $want)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                        @if($want->image_paths && count($want->image_paths) > 0)
                            <div class="h-48 bg-gray-200 dark:bg-gray-700">
                                <img src="{{ asset('storage/' . $want->image_paths[0]) }}" alt="{{ $want->title }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="p-6">
                            <h2 class="text-xl font-bold mb-2">
                                <a href="{{ route('wants.show', $want) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                    {{ $want->title }}
                                </a>
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ Str::limit($want->description, 120) }}</p>

                            @if($want->budget_min || $want->budget_max)
                                <p class="text-sm font-semibold text-green-600 dark:text-green-400 mb-2">
                                    Budget: ${{ number_format($want->budget_min, 0) }} - ${{ number_format($want->budget_max, 0) }}
                                </p>
                            @endif

                            @if($want->city || $want->state)
                                <p class="text-xs text-gray-500 dark:text-gray-500 mb-2">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $want->city }}@if($want->city && $want->state), @endif{{ $want->state }}
                                </p>
                            @endif

                            <small class="text-gray-500 dark:text-gray-500">Posted by: {{ $want->user->name }}</small>

                            @if($want->categories->isNotEmpty())
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @foreach($want->categories->take(3) as $category)
                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">No wants have been posted yet.</p>
                        @auth
                            <a href="{{ route('wants.create') }}" class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-md transition">
                                Post the First Want
                            </a>
                        @endauth
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $wants->links() }}
            </div>
        </div>
    </div>
</x-app-layout>