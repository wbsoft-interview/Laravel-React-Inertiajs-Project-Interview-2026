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
        Schema::create('id_card_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('institute_contact_no')->nullable();
            $table->string('institute_contact_no_2')->nullable();
            $table->string('institute_contact_email')->nullable();
            $table->string('institute_code')->nullable();
            $table->string('emis_code')->nullable();
            $table->string('institute_established')->nullable();
            $table->string('institute_address')->nullable();
            $table->string('image_opacity')->nullable();
            $table->text('sign_image')->nullable();
            $table->text('seal_image')->nullable();
            $table->text('background_image')->nullable();
            $table->text('hologram_image')->nullable();
            $table->text('logo_image')->nullable();
            $table->text('frontend_logo_image')->nullable();
            $table->text('frontend_back_logo_image')->nullable();
            $table->boolean('status')->default(true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('id_card_settings');
    }
};
