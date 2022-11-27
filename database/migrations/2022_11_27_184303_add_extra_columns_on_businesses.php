<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->bigInteger('region_id')->nullable()->unsigned();
            $table->bigInteger('province_id')->nullable()->unsigned();
            $table->bigInteger('district_id')->nullable()->unsigned();
            $table->bigInteger('city_id')->nullable()->unsigned();
            $table->bigInteger('group_id')->nullable()->unsigned();

            $table->foreign('region_id')->references('id')->on('regions')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('group_id')->references('id')->on('business_groups')->onDelete('CASCADE')->onUpdate('CASCADE');
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
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $table->dropColumn('region_id');
            $table->dropColumn('province_id');
            $table->dropColumn('district_id');
            $table->dropColumn('city_id');
            $table->dropColumn('group_id');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
    }
};
