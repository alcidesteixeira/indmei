<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseProductSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_product_specs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('warehouse_product_id');
            $table->string('description');
            $table->string('color');
            $table->string('liquid_weight');
            $table->string('gross_weight');
            $table->string('cost');
            $table->string('threshold');
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
        Schema::dropIfExists('warehouse_product_specs');
    }
}
