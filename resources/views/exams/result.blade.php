<x-app-layout>
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6">Exam Results</h1>
        <p class="text-lg mb-4">Score: <span class="font-semibold">{{ $score }}</span></p>
        <p class="text-lg mb-4">Submitted at: <span class="font-semibold">{{ $submittedAt }}</span></p>

        <h2 class="text-2xl font-semibold mt-6 mb-4">Your Answers</h2>
        <div class="bg-white shadow-lg rounded-lg p-6">
            @foreach ($exam->questions as $question)
                <div class="mb-6 border-b pb-4">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Q{{ $loop->iteration }}. {{ $question->question_text }}
                    </h3>
                    <!-- Question Image -->
                    @if ($question->question_image)
                        <img src="{{ $question->question_image }}" alt="Question Image"
                            class="w-full max-h-64 object-contain mb-4 rounded-lg">
                    @endif

                    <div class="mt-2">
                        @foreach ($question->options as $option)
                            <div class="flex items-center">
                                <input type="checkbox" disabled @if (isset($userAnswers[$question->id]) &&
                                        in_array($option->id, $userAnswers[$question->id]->pluck('option_id')->toArray())) checked @endif>
                                <span
                                    class="ml-2 
                                    @if (in_array($option->id, $question->options->where('is_correct', true)->pluck('id')->toArray())) text-green-600 font-semibold
                                    @else
                                        text-red-600 @endif">
                                    {{ $option->option_text }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-2">
                        <strong class="text-gray-700">Correct Answer:</strong>
                        @foreach ($question->options as $option)
                            @if (in_array($option->id, $question->options->where('is_correct', true)->pluck('id')->toArray()))
                                <span class="text-green-600 font-semibold">{{ $option->option_text }}</span>
                            @endif
                        @endforeach
                    </div>

                    <!-- Explanation Section -->
                    <div class="mt-4">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <strong class="text-gray-700">Explanation:</strong>
                            <p class="text-gray-800 mt-2">{{ $question->explanation }}</p>
                        </div>
                        <!-- Explanation Image -->
                        @if ($question->explanation_image)
                            <img src="{{ $question->explanation_image }}" alt="Explanation Image"
                                class="w-full max-h-64 object-contain mb-4 rounded-lg">
                        @endif

                        @if ($question->explanation)
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
