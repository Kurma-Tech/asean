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
        Schema::table('journal_categories', function (Blueprint $table) {
            $table->renameColumn('acjs_code', 'ajcs_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journal_categories', function (Blueprint $table) {
            $table->renameColumn('ajcs_code', 'acjs_code')->nullable();
        });
    }
};
