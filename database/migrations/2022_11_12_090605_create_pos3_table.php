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
        Schema::create('country_areas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('country_id')->unsigned()->nullable() ;
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('area_name')->nullable();
            $table->string('area_code')->nullable();
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
        Schema::dropIfExists('country_areas');
    }
};
