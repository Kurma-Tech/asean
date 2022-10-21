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
        Schema::table('patents', function (Blueprint $table) {
            $table->renameColumn('patent_id', 'filing_no')->nullable();
            $table->renameColumn('date', 'registration_date')->nullable();
            $table->string('publication_date')->nullable();
            $table->string('filing_date')->nullable();
            $table->string('registration_no')->nullable();
            $table->json('inventor_name')->nullable();
            $table->text('applicant_company')->nullable();
            $table->text('abstract')->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('patent_categories')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patents', function (Blueprint $table) {
            $table->renameColumn('filing_no', 'patent_id');
            $table->renameColumn('registration_date', 'date');
        });
    }
};