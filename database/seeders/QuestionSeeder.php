<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        Question::create([
            'exam_id' => 1,
            'question_text' => 'What is 2 + 2?',
            'explanation' => 'Basic addition problem.',
        ]);

        Question::create([
            'exam_id' => 2,
            'question_text' => 'What is H2O?',
            'explanation' => 'H2O is the chemical formula for water.',
        ]);
    }
}
