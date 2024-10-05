<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;

class ExamSeeder extends Seeder
{
    public function run()
    {
        Exam::create([
            'title' => 'Math Exam',
            'user_id' => 1, // John Doe's exam
            'category_id' => 1,
            'is_published' => true,
        ]);

        Exam::create([
            'title' => 'Science Exam',
            'user_id' => 2, // Jane Smith's exam
            'category_id' => 2,
            'is_published' => false,
        ]);
    }
}
