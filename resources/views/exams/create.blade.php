<x-app-layout>
    {{-- script読み込み --}}
    <x-slot name="script">
        <script>/js/exam/create.js</script>
    </x-slot>
    <section class="bg-gray-100 py-12">
        <div class="container mx-auto max-w-3xl p-8">
            <h1 class="text-4xl font-extrabold text-center text-blue-600 mb-12">Create Exam</h1>

            <form action="{{ route('exams.store') }}" method="POST" enctype="multipart/form-data">
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
                        <div class="flex items-center space-x-4">
                            <textarea name="questions[0][text]"
                                class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                placeholder="Enter your question" rows="3" required></textarea>

                            <!-- 質問の画像アップロードアイコン -->
                            <label class="cursor-pointer flex items-center space-x-2">
                                <input type="file" name="questions[0][question_image]" accept="image/*"
                                    class="hidden question-image-input">
                                <i class="fas fa-image text-blue-500"></i> <!-- アイコン -->
                            </label>
                            <img class="question-image-preview mt-2 hidden"
                                style="max-width: 100px; max-height: 100px;" /> <!-- プレビュー画像 -->
                            <button type="button" class="delete-question text-gray-500 hover:text-gray-700 ml-auto">
                                <i class="fas fa-times fa-lg"></i> <!-- "×" icon -->
                            </button>
                        </div>

                        <!-- Explanationフィールドの追加 -->
                        <label class="block text-gray-700 font-semibold mt-4 mb-2">Explanation</label>
                        <div class="flex items-center space-x-4">
                            <textarea name="questions[0][explanation]"
                                class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                placeholder="Enter explanation" rows="3"></textarea>

                            <!-- 解説の画像アップロードアイコン -->
                            <!-- 解説の画像アップロードアイコン -->
                            <label class="cursor-pointer flex items-center space-x-2">
                                <input type="file" name="questions[0][explanation_image]" accept="image/*"
                                    class="hidden explanation-image-input">
                                <i class="fas fa-image text-blue-500"></i> <!-- アイコン -->
                            </label>
                            <img class="explanation-image-preview mt-2 hidden"
                                style="max-width: 100px; max-height: 100px;" /> <!-- 解説のプレビュー画像 -->
                        </div>

                        <label class="block text-gray-700 font-semibold mt-4 mb-2">Answer Options</label>
                        <div class="options-container">
                            <div class="option mb-4 flex items-center space-x-4">
                                <input type="text" name="questions[0][options][]"
                                    class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    placeholder="Option 1" required>
                                <input type="checkbox" name="questions[0][correct][]" value="0" class="ml-2">
                                Correct
                                <button type="button" class="delete-question text-gray-500 hover:text-gray-700 ml-auto">
                                    <i class="fas fa-times fa-lg"></i> <!-- "×" icon -->
                                </button>
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

    {{-- Scripts --}}
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
                <div class="flex items-center space-x-4">
                    <textarea name="questions[${questionIndex}][text]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter your question" rows="3" required></textarea>
                    <label class="cursor-pointer flex items-center space-x-2">
                        <input type="file" name="questions[${questionIndex}][question_image]" accept="image/*" class="hidden">
                        <i class="fas fa-image text-blue-500"></i> <!-- アイコン -->
                    </label>
                    <button type="button" class="delete-question text-gray-500 hover:text-gray-700 ml-auto">
                        <i class="fas fa-times fa-lg"></i> <!-- 削除ボタン -->
                    </button>
                </div>
    
                <label class="block text-gray-700 font-semibold mt-4 mb-2">Explanation</label>
                <div class="flex items-center space-x-4">
                    <textarea name="questions[${questionIndex}][explanation]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter explanation" rows="3"></textarea>
                    <label class="cursor-pointer flex items-center space-x-2">
                        <input type="file" name="questions[${questionIndex}][explanation_image]" accept="image/*" class="hidden">
                        <i class="fas fa-image text-blue-500"></i> <!-- アイコン -->
                    </label>
                </div>
    
                <label class="block text-gray-700 font-semibold mt-4 mb-2">Answer Options</label>
                <div class="options-container">
                    <div class="option mb-4 flex items-center space-x-4">
                        <input type="text" name="questions[${questionIndex}][options][]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Option ${optionIndex}" required>
                        <input type="checkbox" name="questions[${questionIndex}][correct][]" value="0" class="ml-2"> Correct
                        <button type="button" class="delete-option text-gray-500 hover:text-gray-700 ml-auto">
                            <i class="fas fa-times fa-lg"></i> <!-- 削除ボタン -->
                        </button>
                    </div>
                </div>
                <button type="button" class="add-option text-blue-500 hover:text-blue-700 font-semibold">+ Add another option</button>
            `;
    
            document.getElementById('questions-container').appendChild(questionContainer);
            questionIndex++;
    
            // Add event listeners for the new buttons
            questionContainer.querySelector('.add-option').addEventListener('click', function() {
                optionIndex++;
                const optionContainer = document.createElement('div');
                optionContainer.classList.add('option', 'mb-4', 'flex', 'items-center', 'space-x-4');
                optionContainer.innerHTML = `
                    <input type="text" name="questions[${questionIndex - 1}][options][]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Option ${optionIndex}" required>
                    <input type="checkbox" name="questions[${questionIndex - 1}][correct][]" value="${optionIndex - 1}" class="ml-2"> Correct
                    <button type="button" class="delete-option text-gray-500 hover:text-gray-700 ml-auto">
                        <i class="fas fa-times fa-lg"></i> <!-- 削除ボタン -->
                    </button>
                `;
                questionContainer.querySelector('.options-container').appendChild(optionContainer);
    
                // 削除ボタンのイベントリスナーを追加
                optionContainer.querySelector('.delete-option').addEventListener('click', function() {
                    deleteOption(this);
                });
            });
    
            // 削除ボタンのイベントリスナーを追加
            questionContainer.querySelector('.delete-question').addEventListener('click', function() {
                deleteQuestion(this);
            });
        });
    
        // 初期の「More option」ボタンのイベントリスナーを追加
        document.querySelectorAll('.add-option').forEach((button) => {
            let optionIndex = 1; // Reset option index for each question
            button.addEventListener('click', function() {
                optionIndex++;
                const optionContainer = document.createElement('div');
                optionContainer.classList.add('option', 'mb-4', 'flex', 'items-center', 'space-x-4');
                optionContainer.innerHTML = `
                    <input type="text" name="questions[${questionIndex - 1}][options][]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Option ${optionIndex}" required>
                    <input type="checkbox" name="questions[${questionIndex - 1}][correct][]" value="${optionIndex - 1}" class="ml-2"> Correct
                    <button type="button" class="delete-option text-gray-500 hover:text-gray-700 ml-auto">
                        <i class="fas fa-times fa-lg"></i> <!-- 削除ボタン -->
                    </button>
                `;
                button.previousElementSibling.appendChild(optionContainer);
    
                // 削除ボタンのイベントリスナーを追加
                optionContainer.querySelector('.delete-option').addEventListener('click', function() {
                    deleteOption(this);
                });
            });
        });
    
        // 画像プレビュー機能 (質問画像用)
        document.querySelectorAll('.question-image-input').forEach(input => {
            input.addEventListener('change', function(event) {
                const preview = this.closest('div').querySelector('.question-image-preview');
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    
        // 画像プレビュー機能 (解説画像用)
        document.querySelectorAll('.explanation-image-input').forEach(input => {
            input.addEventListener('change', function(event) {
                const preview = this.closest('div').querySelector('.explanation-image-preview');
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    
        // 削除機能 (Question)
        function deleteQuestion(button) {
            const questionContainer = button.closest('.question');
            if (document.querySelectorAll('.question').length > 1) {
                questionContainer.remove();
            } else {
                alert('At least one question is required.');
            }
        }
    
        // 削除機能 (Option)
        function deleteOption(button) {
            const optionContainer = button.closest('.option');
            const optionsContainer = button.closest('.options-container');
            if (optionsContainer.querySelectorAll('.option').length > 1) {
                optionContainer.remove();
            } else {
                alert('At least one option is required.');
            }
        }
    </script>
</x-app-layout>
    