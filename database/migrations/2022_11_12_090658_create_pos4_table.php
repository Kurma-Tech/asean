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
        Schema::create('country_zips', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('area_id')->unsigned()->nullable() ;
            $table->foreign('area_id')->references('id')->on('country_areas')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('zip_code')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('country_zips');
    }
};
