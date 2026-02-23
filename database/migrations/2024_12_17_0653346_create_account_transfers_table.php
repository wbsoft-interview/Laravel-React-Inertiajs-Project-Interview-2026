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
        Schema::create('account_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_category_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('transfer_by')->nullable();
            $table->string('transfer_type')->nullable();
            $table->double('transfer_amount',8,2)->nullable();
            $table->double('current_amount',8,2)->nullable();
            $table->string('transfer_date')->nullable();
            $table->text('transfer_purpuse')->nullable();
            $table->boolean('status')->default(true);
            $table->foreign('account_category_id')->references('id')->on('account_categories')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('transfer_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_transfers');
    }
};
