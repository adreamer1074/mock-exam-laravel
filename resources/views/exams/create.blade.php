<x-app-layout>
    <section class="bg-gray-100 py-12">
        <div class="container mx-auto max-w-3xl p-8">
            <h1 class="text-4xl font-extrabold text-center text-blue-600 mb-12">Create Exam</h1>

            <form action="{{ route('exams.store') }}" method="POST">
                @csrf

                <!-- Exam Setting Section -->
                <div class="bg-white shadow-lg rounded-lg p-10 mb-12">
                    <h2 class="text-2xl font-semibold text-blue-600 mb-6">Exam Settings</h2>

                    <!-- Category Selection -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Category</label>
                        <select name="category_id"
                            class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            required>
                            <option value="">Select a Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Exam Name -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Exam Name</label>
                        <input type="text" name="name"
                            class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Enter exam name" required>
                    </div>

                    <!-- Is Public Checkbox -->
                    <div class="mb-6 flex items-center">
                        <input type="checkbox" name="is_public" value="1" class="mr-2" checked>
                        <label class="text-gray-700">Is Public?</label>
                    </div>
                </div>

                <!-- Questions and Options Section -->
                <div id="questions-container">
                    <div class="question mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Question</label>
                        <textarea name="questions[0][text]"
                            class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Enter your question" rows="3" required></textarea>

                        <label class="block text-gray-700 font-semibold mt-4 mb-2">Answer Options</label>
                        <div class="options-container">
                            <div class="option mb-4 flex items-center space-x-4">
                                <input type="text" name="questions[0][options][]"
                                    class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    placeholder="Option 1" required>
                                <input type="checkbox" name="questions[0][correct][]" value="0" class="ml-2">
                                Correct
                            </div>
                        </div>
                        <button type="button" class="add-option text-blue-500 hover:text-blue-700 font-semibold">+ Add
                            another option</button>
                    </div>
                </div>

                <button type="button" class="add-question text-blue-500 hover:text-blue-700 font-semibold mt-6">+ Add
                    another question</button>

                <button type="submit"
                    class="mt-8 bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-300">Create
                    Exam</button>
        </div>
        </form>
        </div>
    </section>

    <script>
        let questionIndex = 1;

        // Add new question
        document.querySelector('.add-question').addEventListener('click', function() {
            const questionContainer = document.createElement('div');
            questionContainer.classList.add('question', 'mb-6');

            // Option number reset
            let optionIndex = 1;

            questionContainer.innerHTML = `
                <label class="block text-gray-700 font-semibold mb-2">Question</label>
                <textarea name="questions[${questionIndex}][text]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter your question" rows="3" required></textarea>

                <label class="block text-gray-700 font-semibold mt-4 mb-2">Answer Options</label>
                <div class="options-container">
                    <div class="option mb-4 flex items-center space-x-4">
                        <input type="text" name="questions[${questionIndex}][options][]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Option ${optionIndex}" required>
                        <input type="checkbox" name="questions[${questionIndex}][correct][]" value="0" class="ml-2"> Correct
                    </div>
                </div>
                <button type="button" class="add-option text-blue-500 hover:text-blue-700 font-semibold">+ Add another option</button>
            `;

            document.getElementById('questions-container').appendChild(questionContainer);
            questionIndex++;

            // Add event listener for the new "More option" button
            questionContainer.querySelector('.add-option').addEventListener('click', function() {
                optionIndex++; // Increment option index for each question
                const optionContainer = document.createElement('div');
                optionContainer.classList.add('option', 'mb-4', 'flex', 'items-center', 'space-x-4');
                optionContainer.innerHTML = `
                    <input type="text" name="questions[${questionIndex - 1}][options][]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Option ${optionIndex}" required>
                    <input type="checkbox" name="questions[${questionIndex - 1}][correct][]" value="${optionIndex - 1}" class="ml-2"> Correct
                `;
                questionContainer.querySelector('.options-container').appendChild(optionContainer);
            });
        });

        // Add event listener for the initial "More option" button
        document.querySelectorAll('.add-option').forEach((button) => {
            let optionIndex = 1; // Reset option index for each question
            button.addEventListener('click', function() {
                optionIndex++;
                const optionContainer = document.createElement('div');
                optionContainer.classList.add('option', 'mb-4', 'flex', 'items-center', 'space-x-4');
                optionContainer.innerHTML = `
                    <input type="text" name="questions[0][options][]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Option ${optionIndex}" required>
                    <input type="checkbox" name="questions[0][correct][]" value="${optionIndex - 1}" class="ml-2"> Correct
                `;
                document.querySelector('.question .options-container').appendChild(optionContainer);
            });
        });
    </script>
</x-app-layout>
