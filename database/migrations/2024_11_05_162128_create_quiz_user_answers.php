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
        Schema::create('quiz_user_answers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('id')->autoIncrement();
            $table->integer('quiz_id');
            $table->integer('user_id');
            $table->integer('quiz_question_id');
            $table->integer('quiz_answer_id')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('point')->default(0);
            $table->timestamps();

            $table->foreign('quiz_id')->references('id')->on('quiz');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('quiz_question_id')->references('id')->on('quiz_question');
            $table->foreign('quiz_answer_id')->references('id')->on('quiz_answer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_user_answers');
    }
};
