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
        if (Schema::hasColumn('businesses', 'region_id'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('region_id');
            });
        }
        if (Schema::hasColumn('businesses', 'province_id'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('province_id');
            });
        }
        if (Schema::hasColumn('businesses', 'district_id'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('district_id');
            });
        }
        if (Schema::hasColumn('businesses', 'city_id'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('city_id');
            });
        }
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
