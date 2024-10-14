<x-app-layout>
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6">Create a New Exam</h1>

        @if(session('success'))
            <div class="bg-green-200 text-green-700 p-4 mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('exams.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Exam Name</label>
                <input type="text" name="name" class="w-full border p-2" placeholder="Your Exam Name">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Category</label>
                <select name="category_id" class="w-full border p-2" required>
                    <option value="" disabled selected>Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Description</label>
                <textarea name="description" class="w-full border p-2"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Is Public?</label>
                <select name="is_public" class="" required>
                    <option value="1" selected>Public</option> <!-- Publicがデフォルト -->
                    <option value="0">Private</option>
                </select>
            </div>            

            <div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Create Exam</button>
            </div>
        </form>
    </div>
</x-app-layout>
