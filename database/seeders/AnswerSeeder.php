<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Answer;
use App\Models\Question;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全ての質問に対してダミーの回答を挿入
        $questions = Question::all();

        foreach ($questions as $question) {
            // 正しい回答
            Answer::create([
                'question_id' => $question->id,
                'is_correct' => true,
                'answer_text' => 'This is the correct answer for question ' . $question->id,
            ]);

            // 間違った回答（複数追加）
            Answer::create([
                'question_id' => $question->id,
                'is_correct' => false,
                'answer_text' => 'This is an incorrect answer for question ' . $question->id,
            ]);

            Answer::create([
                'question_id' => $question->id,
                'is_correct' => false,
                'answer_text' => 'This is another incorrect answer for question ' . $question->id,
            ]);
        }
    }
}
