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
        Schema::table('industry_classifications', function (Blueprint $table) {
            $table->renameColumn('psic_code', 'code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('industry_classifications', function (Blueprint $table) {
            $table->renameColumn('code', 'psic_code')->nullable();
        });
    }
};
