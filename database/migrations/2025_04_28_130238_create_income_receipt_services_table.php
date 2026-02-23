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
        Schema::create('income_receipt_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('income_receipt_id')->nullable();
            $table->unsignedBigInteger('income_category_id')->nullable();
            $table->unsignedBigInteger('income_id')->nullable();
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->double('income_amount', 8,2)->nullable();
            $table->text('income_details')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('income_receipt_id')->references('id')->on('income_receipts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('income_category_id')->references('id')->on('income_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('income_id')->references('id')->on('incomes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('receivers')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_receipt_services');
    }
};
