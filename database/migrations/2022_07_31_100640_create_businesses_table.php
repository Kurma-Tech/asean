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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_type_id')->unsigned()->nullable();
            $table->foreign('business_type_id')->references('id')->on('business_types')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->bigInteger('industry_classification_id')->unsigned()->nullable();
            $table->foreign('industry_classification_id')->references('id')->on('industry_classifications')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('year')->nullable();
            $table->string('sec_no')->nullable();
            $table->string('company_name')->nullable();
            $table->string('date_registered')->nullable();
            $table->string('ngc_code')->nullable();
            $table->enum('status', ['REGISTERED', 'UNREGISTERED'])->nullable();
            $table->string('address')->nullable();
            $table->string('industry_code')->nullable();
            $table->text('industry_description')->nullable();
            $table->string('geo_code')->nullable();
            $table->decimal('long', 29, 20)->nullable();
            $table->decimal('lat', 29, 20)->nullable();
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
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropForeign('businesses_business_type_id_foreign');
            $table->dropForeign('businesses_industry_classification_id_foreign');
        });
        
        Schema::dropIfExists('businesses');
    }
};
