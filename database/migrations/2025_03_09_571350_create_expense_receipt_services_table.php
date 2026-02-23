<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseReceiptServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_receipt_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('expense_receipt_id')->nullable();
            $table->unsignedBigInteger('expense_category_id')->nullable();
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->unsignedBigInteger('payee_id')->nullable();
            $table->double('expense_amount', 8,2)->nullable();
            $table->double('grand_total_paid', 8,2)->nullable();
            $table->double('grand_total_due', 8,2)->nullable();
            $table->text('expense_details')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('expense_receipt_id')->references('id')->on('expense_receipts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('expense_category_id')->references('id')->on('expense_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('expense_id')->references('id')->on('expenses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('payee_id')->references('id')->on('payees')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_receipt_services');
    }
}
