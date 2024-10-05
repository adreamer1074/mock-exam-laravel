<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TodoList;
use Illuminate\Support\Facades\DB;

class TodoListSeeder extends Seeder
{
    public function run()
    {
        TodoList::create([
            'user_id' => 1,
            'title' => 'Prepare for Math Exam',
            'content' => 'Review basic addition and subtraction problems.',
            'due_date' => '2024-10-15',
            'notes' => 'Focus on chapters 1 and 2.',
        ]);

        TodoList::create([
            'user_id' => 2,
            'title' => 'Study Science Notes',
            'content' => 'Review chemical formulas and scientific principles.',
            'due_date' => '2024-10-20',
            'notes' => 'Focus on water-related topics.',
        ]);
    }
}
