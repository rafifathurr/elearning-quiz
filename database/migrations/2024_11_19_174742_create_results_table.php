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
        Schema::create('results', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('id')->autoIncrement();
            $table->integer('quiz_id');
            $table->integer('user_id');
            $table->integer('time_duration')->nullable();
            $table->timestamp('start_time')->useCurrent()->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            // Foreign Key
            $table->foreign('quiz_id')->references('id')->on('quiz');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
