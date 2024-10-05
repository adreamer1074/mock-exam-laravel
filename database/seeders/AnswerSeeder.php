<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Answer;

class AnswerSeeder extends Seeder
{
    public function run()
    {
        Answer::create([
            'question_id' => 1,
            'answer_text' => '4',
            'is_correct' => true,
        ]);

        Answer::create([
            'question_id' => 1,
            'answer_text' => '5',
            'is_correct' => false,
        ]);

        Answer::create([
            'question_id' => 2,
            'answer_text' => 'Water',
            'is_correct' => true,
        ]);

        Answer::create([
            'question_id' => 2,
            'answer_text' => 'Hydrogen',
            'is_correct' => false,
        ]);
    }
}
