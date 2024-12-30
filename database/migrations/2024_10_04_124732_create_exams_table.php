<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 各ユーザーが作成した試験をデータベースに挿入し、
     * 試験の公開/非公開設定や管理機能の動作確認が可能です。
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->boolean('is_public')->default(true);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->int('del_flg')->default(null);

        });

        /*
                ALTER TABLE `exams`
                ADD COLUMN `category_id` BIGINT UNSIGNED NOT NULL;
                ALTER TABLE `exams`
                ADD CONSTRAINT `exams_category_id_foreign`
                FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
                ON DELETE CASCADE;  */
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
