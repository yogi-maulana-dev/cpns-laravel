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
        Schema::create('participant_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->integer('correct_answer_count')->default(0);
            $table->integer('unanswered_count')->default(0);
            $table->integer('wrong_answer_count')->default(0);
            $table->integer('total_exam_time_in_second')->default(0);
            $table->integer('total_score')->default(0);
            $table->datetime('started_at')->nullable();
            $table->datetime('end_at')->nullable();
            $table->datetime('finished_at')->nullable();
            $table->boolean('is_passed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_exam_results');
    }
};
