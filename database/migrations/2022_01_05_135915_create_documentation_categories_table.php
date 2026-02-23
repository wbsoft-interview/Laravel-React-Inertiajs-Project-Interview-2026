<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentationCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentation_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('parent_category_id')->nullable();
            $table->string('documentation_type')->nullable();

            // Separate columns for English and Bangla
            $table->string('category_name_en');
            $table->string('slug_en')->nullable();
            $table->text('details_en')->nullable();
            $table->integer('serial_no')->nullable();

            $table->boolean('status')->default(true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('documentation_categories');
    }
}
