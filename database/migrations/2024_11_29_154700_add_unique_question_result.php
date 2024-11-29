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
        Schema::table('result_details', function (Blueprint $table) {
            $table->unique(['result_id', 'question_id'], 'result_question_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('result_details', function (Blueprint $table) {
            $table->dropUnique('result_question_unique');
        });
    }
};
