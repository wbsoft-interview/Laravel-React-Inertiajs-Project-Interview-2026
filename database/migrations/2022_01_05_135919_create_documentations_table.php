<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('published_by_id')->nullable();
            $table->json('documentation_category_id')->nullable();
            $table->json('documentation_tag_id')->nullable();
            $table->text('title_en')->nullable();
            $table->longText('post_en')->nullable();
            $table->longText('photo_text_en')->nullable();
            $table->string('photo')->nullable();
            $table->time('publish_time')->nullable();
            $table->date('publish_date')->nullable();
            $table->string('layout_format')->nullable();
            $table->text('slug_en')->nullable();
            $table->text('permalink_slug')->nullable();
            $table->bigInteger('views')->default(0);
            $table->tinyinteger('is_published')->default(1);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('published_by_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('documentations');
    }
}
