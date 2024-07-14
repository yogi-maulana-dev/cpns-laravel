<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_score_qgts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_exam_result_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_group_type_id')->constrained();
            $table->integer('total_score')->default(0);
            $table->boolean('is_passed');
            $table->integer('correct_answer_count')->default(0);
            $table->integer('unanswered_count')->default(0);
            $table->integer('wrong_answer_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_score_qgts');
    }
};
