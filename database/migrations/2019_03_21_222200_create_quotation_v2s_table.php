<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationV2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_v2s', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('reference');
            $table->string('client');
            $table->string('date');
            $table->string('defect_percentage');
            $table->string('company_cost_percentage');
            $table->string('comission_percentage');
            $table->string('transportation_percentage');
            $table->string('extra_percentage');
            $table->string('extra_2_percentage');
            $table->string('client_price');
            $table->string('product_image');
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
        Schema::dropIfExists('quotation_v2s');
    }
}
