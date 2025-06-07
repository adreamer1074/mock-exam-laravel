<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Call the UserSeeder to seed the users table
        $this->call(UserSeeder::class);

        // Call the ExamCategorySeeder to seed the categories table
        $this->call(ExamCategorySeeder::class);

        // Call the ExamSeeder to seed the exams table
        $this->call(ExamSeeder::class);

        // Call the QuestionSeeder to seed the questions table
        $this->call(QuestionSeeder::class);

        // Call the AnswerSeeder to seed the answers table
        $this->call(AnswerSeeder::class);

        // Call the TodoListSeeder to seed the todo_lists table
        $this->call(TodoListSeeder::class);

        // Call the ExamResultSeeder to seed the results table
        $this->call(ExamResultSeeder::class);
    }
}
