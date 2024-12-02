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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('total_price')->default(0)->nullable()->change();
            $table->string('payment_method')->nullable()->change();
            $table->integer('status')->nullable()->change();
            $table->dropColumn('attachment');
            $table->dropColumn('class');
            $table->dropForeign(['package_id']);
            $table->dropColumn('package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
