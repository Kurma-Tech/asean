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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        if (Schema::hasColumn('businesses', 'ngc_code'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('ngc_code');
            });
        }
        if (Schema::hasColumn('businesses', 'industry_code'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('industry_code');
            });
        }
        if (Schema::hasColumn('businesses', 'industry_description'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('industry_description');
            });
        }
        if (Schema::hasColumn('businesses', 'geo_code'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('geo_code');
            });
        }
        if (Schema::hasColumn('businesses', 'geo_description'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('geo_description');
            });
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
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
