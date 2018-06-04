<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSampleArticlesWiresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_articles_wires', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sample_article_id');
            $table->string('step_id');
            $table->string('warehouse_product_id');
            $table->string('grams');
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
        Schema::dropIfExists('sample_articles_wires');
    }
}
