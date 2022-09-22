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
        Schema::create('classification_manpowers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('classification_id')->unsigned()->nullable();
            $table->foreign('classification_id')->references('id')->on('industry_classifications')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->bigInteger('manpower_id')->unsigned()->nullable();
            $table->foreign('manpower_id')->references('id')->on('manpowers')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('seats')->default(0);
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
        Schema::dropIfExists('classification_manpowers');
    }
};
