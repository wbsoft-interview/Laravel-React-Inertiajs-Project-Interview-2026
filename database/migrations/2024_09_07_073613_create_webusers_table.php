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
        Schema::create('webusers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('verify_code')->nullable();
            $table->dateTime('verify_expires_at')->nullable();
            $table->boolean('status')->default(0);
            $table->string('gender')->nullable();
            $table->string('role')->default('user');
            $table->string('image')->nullable();
            $table->string('admin_id')->nullable();
            $table->string('manager_id')->nullable();
            $table->text('address')->nullable();
            $table->text('device_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webusers');
    }
};
