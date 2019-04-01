<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorsToSampleArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_articles', function($table) {
            $table->string('cor1')->default('cor1');
            $table->string('cor2')->default('cor2');
            $table->string('cor3')->default('cor3');
            $table->string('cor4')->default('cor4');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample_articles', function($table) {
            $table->dropColumn('cor1');
            $table->dropColumn('cor2');
            $table->dropColumn('cor3');
            $table->dropColumn('cor4');
        });
    }
}
