<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 各試験に関連付けられた質問と解説が挿入され、
     * ユーザーが問題を見直す機能をテストするためのデータが生成されます。
     */
    public function up(): void
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->text('question_text');
            $table->text('explanation')->nullable();
            $table->timestamps();
    
            // 外部キー制約の追加を一時的にコメントアウト
            // $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
                /*ALTER TABLE exam_questions
                ADD CONSTRAINT exam_questions_exam_id_foreign
                FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE;

                */
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
