<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTciketBillPayBkashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tciket_bill_pay_bkashes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ticketing_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('merchant_invoice_number')->nullable();
            $table->string('currency')->nullable();
            $table->string('intent')->nullable();
            $table->double('total_amount', 8,2)->nullable();
            $table->boolean('is_bkash_payment')->default(0);
            $table->boolean('is_bkash_execute')->default(0);
            $table->longText('token_id')->nullable();
            $table->string('trx_id')->nullable();
            $table->string('status')->nullable();
            $table->foreign('ticketing_id')->references('id')->on('admin_package_histories')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('tciket_bill_pay_bkashes');
    }
}
