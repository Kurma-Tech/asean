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
        Schema::table('businesses', function (Blueprint $table) {
            $table->bigInteger('region_id')->nullable();
            $table->bigInteger('province_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('group_id')->nullable();

            $table->foreign('region_id')->references('id')->on('regions')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('businesses', function (Blueprint $table) {
            //
        });
    }
};
