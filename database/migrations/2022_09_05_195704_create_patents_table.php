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
        Schema::create('patents', function (Blueprint $table) {
            $table->id();
            $table->string('patent_id')->nullable();
            $table->string('title')->nullable();
            $table->bigInteger('kind_id')->unsigned()->nullable();
            $table->foreign('kind_id')->references('id')->on('patent_kinds')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->bigInteger('type_id')->unsigned()->nullable();
            $table->foreign('type_id')->references('id')->on('patent_types')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('date')->nullable();
            $table->decimal('lat', 29, 20)->nullable();
            $table->decimal('long', 29, 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patents');
    }
};
