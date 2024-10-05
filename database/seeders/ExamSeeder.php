<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;

class ExamSeeder extends Seeder
{
    public function run()
    {
        Exam::create([
            'name' => 'Math Exam',
            'user_id' => 1, // 
            'category_id' => 1,
            'is_public' => true,
        ]);

        Exam::create([
            'name' => 'Science Exam',
            'user_id' => 2, //
            'category_id' => 2,
            'is_public' => false,
        ]);
    }
}
