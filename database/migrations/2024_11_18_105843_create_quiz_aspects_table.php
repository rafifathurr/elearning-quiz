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
        Schema::create('quiz_aspects', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('id')->autoIncrement();
            $table->integer('id_quiz');
            $table->integer('id_aspect');
            $table->integer('level');
            $table->integer('total_question');

            // Foreign Key
            $table->foreign('id_quiz')->references('id')->on('quiz');
            $table->foreign('id_aspect')->references('id')->on('aspect_questions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_aspects');
    }
};
