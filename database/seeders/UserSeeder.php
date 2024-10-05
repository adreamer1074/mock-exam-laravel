<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // すでに存在する場合はスキップ
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'password' => bcrypt('password'), // パスワードをハッシュ化
                'role' => 'admin',
                'favorites' => json_encode([]), // 空の配列をJSON形式で保存
            ]
        );

        // 他のユーザーも同様に追加
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'standard user',
                'password' => bcrypt('password'),
                'role' => 'standard',
                'favorites' => json_encode([]),
            ]
        );
    }
}