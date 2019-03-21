<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationV2SpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_v2_specs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quotation_v2_id');
            $table->string('material');
            $table->string('manual_percentage');
            $table->string('kgs');
            $table->string('price');
            $table->string('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_v2_specs');
    }
}
