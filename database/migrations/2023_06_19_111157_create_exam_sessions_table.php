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
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->string('description', 512)->nullable();
            $table->string('code', 10)->unique();
            $table->tinyInteger('order_of_question')->default(0);
            $table->integer('time');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->tinyInteger('result_display_status')->default(0);
            $table->bigInteger('created_by')->unsigned()->index();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('last_updated_by')->unsigned()->index()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_sessions');
    }
};
