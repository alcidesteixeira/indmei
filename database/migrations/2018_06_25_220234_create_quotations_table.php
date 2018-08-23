<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->string('order_sample_cost_1');
            $table->string('order_sample_cost_2');
            $table->string('order_sample_cost_3');
            $table->string('order_sample_cost_4');
            $table->string('tags');
            $table->string('boxes');
            $table->string('defect');
            $table->string('manpower');
            $table->string('other_costs');
            $table->string('value_sent');
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
        Schema::dropIfExists('quotations');
    }
}
