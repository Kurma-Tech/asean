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
        Schema::table('patent_categories', function (Blueprint $table) {
            $table->integer('section_id')->nullable();
            $table->integer('division_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('class_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patent_categories', function (Blueprint $table) {
            $table->dropColumn('section_id');
            $table->dropColumn('division_id');
            $table->dropColumn('group_id');
            $table->dropColumn('class_id');
        });
    }
};
