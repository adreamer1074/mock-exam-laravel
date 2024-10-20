<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ユーザーごとのデータが生成され、各ユーザーのロールや他のデータが定義されることで、
     * アプリケーションの権限管理やユーザーごとの動作確認が行えます。
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'standard', 'editor']);
            $table->text('favorites')->nullable(); // JSON or serialized list of exam IDs
            $table->timestamps();
            $table->softDeletes(); // Logical delete
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
