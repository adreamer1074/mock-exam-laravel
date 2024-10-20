<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamResult;
use Illuminate\Support\Facades\DB;

class ExamResultSeeder extends Seeder
{
    public function run()
    {
        ExamResult::create([
            'exam_id' => 1,
            'user_id' => 1,
            'score' => 95.5,
        ]);

        ExamResult::create([
            'exam_id' => 2,
            'user_id' => 2,
            'score' => 88.0,
        ]);
    }
}
