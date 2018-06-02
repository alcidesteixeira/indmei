<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSampleArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('reference');
            $table->string('description');
            $table->string('image_url');
            $table->string('status_id');
            $table->string('pe');
            $table->string('perna');
            $table->string('punho');
            $table->string('malha');
            $table->string('maq');
            $table->string('forma');
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
        Schema::dropIfExists('sample_articles');
    }
}
