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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('picture')->default('participant-pictures/default.jpg');
            $table->string('nik', 16)->unique();
            $table->string('name', 50);
            $table->string('address', 128);
            $table->string('place_of_birth', 128);
            $table->date('date_of_birth');
            $table->boolean('gender')->default(0); // 0 == L, 1 == P
            $table->string('phone_number', 25);
            $table->foreignId('user_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
