<x-app-layout>
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6">Exam Results</h1>
        <p class="text-lg mb-4">Score: {{ $score }}</p>
        <p class="text-lg mb-4">Submitted at: {{ $submittedAt }}</p>

        <h2 class="text-2xl font-semibold mt-6 mb-4">Your Answers</h2>
        <div class="bg-white shadow-lg rounded-lg p-6">
            @foreach ($exam->questions as $question)
                <div class="mb-4">
                    <h3 class="font-semibold">{{ $question->text }}</h3>
                    <div>
                        @foreach ($question->options as $option)
                            <div class="flex items-center">
                                <input type="checkbox" disabled
                                       @if(isset($answers[$question->id]) && in_array($option->id, $answers[$question->id])) checked @endif>
                                <span class="ml-2 
                                    @if(in_array($option->id, $question->options->where('is_correct', true)->pluck('id')->toArray()))
                                        text-green-600
                                    @else
                                        text-red-600
                                    @endif">
                                    {{ $option->option_text }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-2">
                        <strong>Correct Answer:</strong>
                        @foreach ($question->options as $option)
                            @if(in_array($option->id, $question->options->where('is_correct', true)->pluck('id')->toArray()))
                                <span class="text-green-600">{{ $option->option_text }}</span>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
