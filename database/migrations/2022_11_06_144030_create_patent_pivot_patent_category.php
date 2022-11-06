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
        Schema::create('patent_pivot_patent_category', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patent_id')->unsigned()->nullable() ;
            $table->foreign('patent_id')->references('id')->on('patents')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->bigInteger('category_id')->unsigned()->nullable() ;
            $table->foreign('category_id')->references('id')->on('patent_categories')->onDelete('CASCADE')->onUpdate('CASCADE');
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
        Schema::dropIfExists('patent_pivot_patent_category');
    }
};
