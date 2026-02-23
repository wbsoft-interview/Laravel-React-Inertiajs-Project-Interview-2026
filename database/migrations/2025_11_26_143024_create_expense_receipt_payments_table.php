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
        Schema::create('expense_receipt_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('expense_receipt_id')->nullable();
            $table->double('total_product', 8,2)->nullable();
            $table->double('total_amount', 8,2)->nullable();
            $table->double('special_discount', 8,2)->default(0);
            $table->double('net_amount', 8,2)->nullable();
            $table->double('paid_amount', 8,2)->nullable();
            $table->double('due_amount', 8,2)->nullable();
            $table->double('change_amount', 8,2)->nullable();
            $table->text('payment_note')->nullable();
            $table->string('billing_month')->nullable();
            $table->string('billing_date')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('expense_receipt_id')->references('id')->on('expense_receipts')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_receipt_payments');
    }
};
