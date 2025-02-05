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
        Schema::table('order_packages', function (Blueprint $table) {
            $table->integer('date_class_id')->nullable();
            $table->foreign('date_class_id')->references('id')->on('date_classes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_packages', function (Blueprint $table) {
            //
        });
    }
};
