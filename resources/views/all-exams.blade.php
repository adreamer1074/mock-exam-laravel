<x-app-layout>
    <section class="bg-gray-100 py-12">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold text-center mb-8">All Exams</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($allExams as $exam)
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h2 class="text-xl font-bold">{{ $exam->name }}</h2>
                        <p>{{ $exam->description }}</p>
                        <a href="{{ route('exams.index')}}" class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                            Take Exam
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
