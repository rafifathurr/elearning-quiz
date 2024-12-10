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
        Schema::table('class_packages', function (Blueprint $table) {
            $table->integer('package_id')->nullable();
            $table->foreign('package_id')->references('id')->on('packages');

            $table->dropForeign(['order_package_id']);
            $table->dropColumn('order_package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_packages', function (Blueprint $table) {
            //
        });
    }
};
