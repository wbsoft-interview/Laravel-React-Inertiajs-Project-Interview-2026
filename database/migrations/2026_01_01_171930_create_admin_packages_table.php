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
        Schema::create('admin_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('package_by')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('sms_remaining');
            $table->integer('student_remaining');
            $table->enum('status', ['active', 'expired', 'upgraded'])->default('active');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_packages');
    }
};
