<x-app-layout>

    <section class="bg-gray-100 py-12">
        <div class="container mx-auto">
            <!-- Welcome section -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-blue-600">Welcome to Poefy</h1>
                <p class="text-lg text-gray-600 mt-2">Your platform for practicing mock exams and sharpening your skills!</p>
            </div>

            <!-- Categories listing section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $category)
                    <div class="bg-white shadow-lg rounded-lg p-6 cursor-pointer" 
                         onclick="window.location='{{ route('exams.category', $category->id) }}'">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $category->name }}</h2>
                        <p class="text-gray-600 mt-2">Description: {{ $category->description }}</p>
                        <a class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                            View Exams
                        </a>
                    </div>
                @endforeach 
            </div>
            <!-- Pagination if necessary -->
            <div class="mt-8">
                {{-- {{ $categories->links() }} <!-- Add pagination links --> --}}
            </div>
        </div>
    </section>

</x-app-layout>
