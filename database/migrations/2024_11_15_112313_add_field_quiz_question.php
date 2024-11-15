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
        Schema::table('quiz_question', function (Blueprint $table) {
            $table->longText('attachment')->nullable();
            $table->text('level')->nullable()->change();
            $table->text('aspect')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_question', function (Blueprint $table) {
            //
        });
    }
};
