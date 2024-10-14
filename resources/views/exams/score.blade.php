<x-app-layout>
    <section class="bg-gray-100 py-12">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold mb-4">結果</h1>
            <h2 class="text-xl">あなたのスコア: {{ $result->score }}</h2>

            @foreach($userAnswers as $answer)
                <div class="mb-4">
                    <h2 class="text-xl font-semibold">{{ $answer->question->text }}</h2>
                    <p>あなたの選択: {{ $answer->option->option_text }}</p>
                    <p class="text-gray-600">正解: {{ $answer->question->correctOption->option_text }}</p>
                </div>
            @endforeach
        </div>
    </section>
</x-app-layout>
