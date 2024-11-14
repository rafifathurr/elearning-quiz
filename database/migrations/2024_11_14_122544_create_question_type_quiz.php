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
        Schema::create('question_type_quiz', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('question_id');
            $table->integer('type_quiz_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign Key
            $table->foreign('question_id')->references('id')->on('quiz_question');
            $table->foreign('type_quiz_id')->references('id')->on('type_quiz');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_type_quiz');
    }
};
