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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_text', 2048);
            $table->string('question_image', 128)->nullable();
            $table->string('discussion_image', 128)->nullable();
            $table->string('discussion', 2048)->nullable();
            $table->foreignId('question_type_id')->constrained();
            $table->tinyInteger('order_index_correct_answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
