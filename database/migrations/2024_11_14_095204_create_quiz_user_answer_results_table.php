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
        Schema::create('quiz_user_answer_results', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('quiz_user_result_id');
            $table->integer('quiz_user_answer_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign Key
            $table->foreign('quiz_user_result_id')->references('id')->on('quiz_user_results');
            $table->foreign('quiz_user_answer_id')->references('id')->on('quiz_user_answers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_user_answer_results');
    }
};
