<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 試験の結果データが生成され、
     * ユーザーが試験を完了してスコアが保存される動作をテストすることができます。
     */
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('user_id'); // ユーザーID
            $table->decimal('score', 5, 2);
            $table->timestamps();
            // 外部キー制約の追加するのはmysql cmdで実行
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
                /* ALTER TABLE results
                 ADD CONSTRAINT results_exam_id_foreign
                 FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE;*/

        });
        
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
