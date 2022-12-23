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
        Schema::table('patent_pivot_patent_category', function (Blueprint $table) {
            $table->string('parent_classification_id')->nullable();
            $table->string('year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patent_pivot_patent_category', function (Blueprint $table) {
            $table->dropColumn('parent_classification_id');
            $table->dropColumn('year');
        });
    }
};
