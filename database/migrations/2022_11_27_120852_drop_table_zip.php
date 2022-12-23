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
        if (Schema::hasTable('country_zips')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Schema::dropIfExists('country_zips');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('country_zips', function (Blueprint $table) {
            //
        });
    }
};
