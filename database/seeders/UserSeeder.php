<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'favorites' => json_encode([]),
        ]);

        User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'standard',
            'favorites' => json_encode([]),
        ]);
    }
}
