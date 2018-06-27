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
            $table->string('sample_article_status_id');
            $table->string('tamanho1');
            $table->string('pe1');
            $table->string('perna1');
            $table->string('punho1');
            $table->string('malha1');
            $table->string('maq1');
            $table->string('forma1');
            $table->string('tamanho2');
            $table->string('pe2');
            $table->string('perna2');
            $table->string('punho2');
            $table->string('malha2');
            $table->string('maq2');
            $table->string('forma2');
            $table->string('tamanho3');
            $table->string('pe3');
            $table->string('perna3');
            $table->string('punho3');
            $table->string('malha3');
            $table->string('maq3');
            $table->string('forma3');
            $table->string('tamanho4');
            $table->string('pe4');
            $table->string('perna4');
            $table->string('punho4');
            $table->string('malha4');
            $table->string('maq4');
            $table->string('forma4');
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
