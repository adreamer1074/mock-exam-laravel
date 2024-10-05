<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamCategory;
use Illuminate\Support\Facades\DB;

class ExamCategorySeeder extends Seeder
{
    public function run()
    {
        ExamCategory::create(['name' => 'Math']);
        ExamCategory::create(['name' => 'Science']);
    }
}
