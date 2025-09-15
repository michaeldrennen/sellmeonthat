<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wants') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($wants as $want)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold mb-2">
                            <a href="{{ route('wants.show', $want) }}" class="text-blue-600 hover:text-blue-800">{{ $want->title }}</a>
                        </h2>
                        <p class="text-gray-600 mb-4">{{ Str::limit($want->description, 150) }}</p>
                        <small class="text-gray-500">Posted by: {{ $want->user->name }}</small>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg shadow-md p-6">
                        <p>No wants have been posted yet.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $wants->links() }}
            </div>
        </div>
    </div>
</x-app-layout>