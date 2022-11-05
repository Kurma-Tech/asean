<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('abstract')->nullable();
            $table->json('author_name')->nullable();
            $table->text('publisher_name')->nullable();
            $table->string('issn_no')->nullable();
            $table->string('cited_score')->nullable();
            $table->string('link')->nullable();
            $table->text('source_title')->nullable();
            $table->bigInteger('country_id')->unsigned()->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->json('categories')->nullable();
            $table->string('published_year')->nullable();
            $table->json('keywords')->nullable();
            $table->decimal('lat', 29, 20)->nullable();
            $table->decimal('long', 29, 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journals');
    }
};