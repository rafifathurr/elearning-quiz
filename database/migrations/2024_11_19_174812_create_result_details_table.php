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
        Schema::create('result_details', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('id')->autoIncrement();
            $table->integer('result_id');
            $table->integer('question_id');
            $table->json('question_detail');
            $table->string('answer')->nullable();
            $table->string('display_time')->nullable();
            $table->integer('order')->nullable();
            $table->integer('aspect_id')->nullable();
            $table->integer('level')->nullable();
            $table->integer('score')->default(0)->nullable();
            $table->timestamps();

            // Foreign Key
            $table->foreign('result_id')->references('id')->on('results');
            $table->foreign('question_id')->references('id')->on('quiz_question');
            $table->foreign('aspect_id')->references('id')->on('aspect_questions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_details');
    }
};
