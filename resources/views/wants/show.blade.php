<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $want->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('wants.index') }}" class="text-blue-600 hover:text-blue-800">&laquo; Back to All Wants</a>
                    </div>

                    <h1 class="text-4xl font-bold mb-2">{{ $want->title }}</h1>
                    <p class="text-gray-500 mb-6">Posted by: {{ $want->user->name }}</p>

                    <div class="prose max-w-none mb-6">
                        <p>{{ $want->description }}</p>
                    </div>

                    @if($want->budget_min || $want->budget_max)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xl font-semibold text-gray-700">
                                Budget: ${{ number_format($want->budget_min, 2) }} - ${{ number_format($want->budget_max, 2) }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-3xl font-bold mb-6">Offers</h2>

                    @auth
                        @if(auth()->user()->roles()->where('slug', 'retailer')->exists())
                            <form action="{{ route('wants.offers.store', $want) }}" method="POST" class="mb-8 bg-gray-50 p-6 rounded-lg">
                                @csrf
                                <h3 class="text-2xl font-semibold mb-4">Make Your Offer</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div class="md:col-span-1">
                                        <label for="price" class="block text-gray-700 font-bold mb-2">Price ($)</label>
                                        <input type="number" step="0.01" name="price" id="price" class="shadow appearance-none border rounded w-full py-2 px-3" required>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="message" class="block text-gray-700 font-bold mb-2">Message (Optional)</label>
                                        <input type="text" name="message" id="message" class="shadow appearance-none border rounded w-full