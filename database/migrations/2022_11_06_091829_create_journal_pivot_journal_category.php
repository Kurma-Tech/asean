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
        Schema::create('journal_pivot_journal_category', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('journal_id')->unsigned()->nullable() ;
            $table->foreign('journal_id')->references('id')->on('journals')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->bigInteger('category_id')->unsigned()->nullable() ;
            $table->foreign('category_id')->references('id')->on('journal_categories')->onDelete('CASCADE')->onUpdate('CASCADE');
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
        Schema::dropIfExists('journal_pivot_journal_category');
    }
};
