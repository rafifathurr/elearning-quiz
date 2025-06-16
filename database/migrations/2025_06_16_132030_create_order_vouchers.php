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
        Schema::create('order_vouchers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('id')->autoIncrement();
            $table->integer('order_id');
            $table->integer('voucher_id');
            $table->integer('package_id');
            $table->integer('price');
            $table->integer('status');
            $table->string('voucher_code');
            $table->string('type_voucher');
            $table->integer('voucher_value');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign Key
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('voucher_id')->references('id')->on('vouchers');
            $table->foreign('package_id')->references('id')->on('packages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_vouchers');
    }
};
