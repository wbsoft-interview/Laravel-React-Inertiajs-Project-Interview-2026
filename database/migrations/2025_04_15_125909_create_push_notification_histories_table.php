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
        Schema::create('push_notification_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('sms_from_id')->nullable();
            $table->unsignedBigInteger('sms_to_id')->nullable();
            $table->unsignedBigInteger('sms_template_id')->nullable();
            $table->unsignedBigInteger('push_notification_id')->nullable();
            $table->string('title')->nullable();
            $table->longText('details')->nullable();
            $table->boolean('status')->default(true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sms_from_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sms_to_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sms_template_id')->references('id')->on('s_m_s_templates')->onDelete('cascade');
            $table->foreign('push_notification_id')->references('id')->on('push_notifications')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_notification_histories');
    }
};
