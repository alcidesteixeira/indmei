<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('sample_article_id')->nullable();
            $table->string('client_id');
            $table->string('client_identifier')->unique();
            $table->string('delivery_date');
            $table->string('description');
            $table->string('cor1')->nullable();
            $table->string('cor2')->nullable();
            $table->string('cor3')->nullable();
            $table->string('cor4')->nullable();
            $table->string('tamanho11')->nullable();
            $table->string('tamanho12')->nullable();
            $table->string('tamanho13')->nullable();
            $table->string('tamanho14')->nullable();
            $table->string('tamanho21')->nullable();
            $table->string('tamanho22')->nullable();
            $table->string('tamanho23')->nullable();
            $table->string('tamanho24')->nullable();
            $table->string('tamanho31')->nullable();
            $table->string('tamanho32')->nullable();
            $table->string('tamanho33')->nullable();
            $table->string('tamanho34')->nullable();
            $table->string('tamanho41')->nullable();
            $table->string('tamanho42')->nullable();
            $table->string('tamanho43')->nullable();
            $table->string('tamanho44')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
