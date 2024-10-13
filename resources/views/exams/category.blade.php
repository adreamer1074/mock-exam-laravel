<x-app-layout>
    <section class="bg-gray-100 py-12">
        <div class="container mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-blue-600">{{ $category->name }} Exams</h1>
                <p class="text-lg text-gray-600 mt-2">Explore exams in the {{ $category->name }} category!</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if($exams->isEmpty())
                    <p class="text-center">No exams found in this category.</p>
                @else
                    @foreach($exams as $exam)
                        <div class="bg-white shadow-lg rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-800">{{ $exam->name }}</h2>
                            <p class="text-gray-600 mt-2">{{ $exam->description }}</p>
                            <p class="text-gray-500 mt-2">Created by: {{ $exam->user->name }}</p>
                            <p class="text-gray-500 mt-2">Views: {{ $exam->views }}</p>
                            <a href="#" class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                                Take Exam
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
</x-app-layout>
