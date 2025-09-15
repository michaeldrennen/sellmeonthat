<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Want - sellmeonthat.com</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold mb-6">Edit Your Want</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('wants.update', $want) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
                <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('title', $want->title) }}" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea name="description" id="description" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('description', $want->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="budget_min" class="block text-gray-700 font-bold mb-2">Minimum Budget ($)</label>
                    <input type="number" name="budget_min" id="budget_min" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('budget_min', $want->budget_min) }}">
                </div>
                <div>
                    <label for="budget_max" class="block text-gray-700 font-bold mb-2">Maximum Budget ($)</label>
                    <input type="number" name="budget_max" id="budget_max" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('budget_max', $want->budget_max) }}">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Want
                </button>
                <a href="{{ route('wants.show', $want) }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
            </div>
        </form>

        <div class="border-t mt-6 pt-6">
            <form action="{{ route('wants.destroy', $want) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this want?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 font-bold">
                    Delete Want
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>