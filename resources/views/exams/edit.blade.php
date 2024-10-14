<x-app-layout>
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6">Edit Exam</h1>

        @if(session('success'))
            <div class="bg-green-200 text-green-700 p-4 mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('exams.update', $exam->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700">Exam Name</label>
                <input type="text" name="name" class="w-full border p-2" value="{{ $exam->name }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Category</label>
                <select name="category_id" class="w-full border p-2" required>
                    <!-- Populate with available categories -->
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Description</label>
                <textarea name="description" class="w-full border p-2">{{ $exam->description }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Is Public?</label>
                <input type="checkbox" name="is_public" value="1" @if($exam->is_public) checked @endif>
            </div>

            <div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Update Exam</button>
            </div>
        </form>

        <form method="POST" action="{{ route('exams.destroy', $exam->id) }}" class="mt-6">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded">Delete Exam</button>
        </form>
    </div>
</x-app-layout>
