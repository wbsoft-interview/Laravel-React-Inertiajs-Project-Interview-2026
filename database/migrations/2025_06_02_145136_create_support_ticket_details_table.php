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
        Schema::create('support_ticket_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('ticket_by_id')->nullable();
            $table->unsignedBigInteger('ticket_reply_id')->nullable();
            $table->unsignedBigInteger('support_ticket_id')->nullable();
            $table->string('subject');
            $table->text('details');
            $table->string('image')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ticket_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ticket_reply_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('support_ticket_id')->references('id')->on('support_tickets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_ticket_details');
    }
};
