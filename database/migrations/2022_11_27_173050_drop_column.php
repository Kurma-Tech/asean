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
        if (Schema::hasColumn('businesses', 'region_id'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropForeign('businesses_region_id_foreign');
                $table->dropColumn('region_id');
            });
        }
        if (Schema::hasColumn('businesses', 'province_id'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropForeign('businesses_province_id_foreign');
                $table->dropColumn('province_id');
            });
        }
        if (Schema::hasColumn('businesses', 'district_id'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropForeign('businesses_district_id_foreign');
                $table->dropColumn('district_id');
            });
        }
        if (Schema::hasColumn('businesses', 'city_id'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropForeign('businesses_city_id_foreign');
                $table->dropColumn('city_id');
            });
        }
        if (Schema::hasColumn('businesses', 'group_id'))
        {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropForeign('businesses_group_id_foreign');
                $table->dropColumn('group_id');
            });
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
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
