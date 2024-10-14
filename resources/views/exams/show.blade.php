<x-app-layout>
    <section class="bg-gray-100 py-12">
        <div class="container mx-auto">
            <h1 class="text-4xl font-bold text-blue-600 mb-8">{{ $exam->name }}</h1>

            <form method="POST" action="{{ route('exam.submit', $exam->id) }}">
                @csrf

                @foreach ($exam->questions as $question)
                    <div class="question mb-8 p-6 bg-white shadow-lg rounded-lg">
                        <h3 class="text-xl font-semibold text-gray-800">Q{{ $loop->iteration }}.
                            {{ $question->question_text }}</h3>

                        {{-- 選択肢の表示 --}}
                        @if ($question->options->count() > 4)
                            <div class="mt-4">
                                @foreach ($question->options as $option)
                                    <label class="block mt-2">
                                        <input type="checkbox" name="answers[{{ $question->id }}][]"
                                            value="{{ $option->id }}" class="mr-2">
                                        {{ $option->option_text }}
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-4">
                                @foreach ($question->options as $option)
                                    <label class="block mt-2">
                                        <input type="radio" name="answers[{{ $question->id }}]"
                                            value="{{ $option->id }}" class="mr-2">
                                        {{ $option->option_text }}
                                    </label>
                                @endforeach
                            </div>
                        @endif

                    </div>
                @endforeach

                <button type="submit" class="mt-4 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Submit
                    Exam</button>
            </form>
        </div>
    </section>

    {{-- Include JavaScript for checkbox validation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    form.addEventListener('submit', function (event) {
        let valid = true;

        // Checkbox validation
        document.querySelectorAll('.question').forEach(question => {
            const checkboxes = question.querySelectorAll('input[type="checkbox"]');
            const checked = Array.from(checkboxes).filter(checkbox => checkbox.checked).length;

            // Optionally set conditions for number of selected answers
            // You can customize the number of required selections if needed
            const optionCount = checkboxes.length;
            if (optionCount === 5 && checked !== 2) {
                alert('この問題では、2つの選択肢を選択する必要があります。');
                valid = false;
            } else if (optionCount === 6 && checked !== 3) {
                alert('この問題では、3つの選択肢を選択する必要があります。');
                valid = false;
            }
        });

        // Allow submission if no answers were selected
        if (!valid) {
            event.preventDefault(); // Prevent form submission
        }
    });
});

    </script>
</x-app-layout>
