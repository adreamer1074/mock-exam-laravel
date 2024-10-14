<x-app-layout>
    <section class="bg-gray-100 py-12">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">All Exams</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($exams as $exam)
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $exam->name }}</h2>
                        <p class="text-gray-600">Category: {{ $exam->category->name }}</p>
                        <p class="text-gray-600">Created by: {{ $exam->user->name }}</p>
                        <p class="text-gray-600 mt-2">{{ $exam->description }}</p>
                        <a href="{{ route('exams.show', $exam->id) }}" class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                            View Exam
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
