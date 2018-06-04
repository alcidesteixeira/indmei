<?php

use App\SampleArticleColor;
use Illuminate\Database\Seeder;

class SampleArticleColorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '1';
        $wireColor->warehouse_product_spec_id = '1';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '1';
        $wireColor->warehouse_product_spec_id = '2';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '1';
        $wireColor->warehouse_product_spec_id = '2';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '1';
        $wireColor->warehouse_product_spec_id = '2';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '2';
        $wireColor->warehouse_product_spec_id = '3';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '2';
        $wireColor->warehouse_product_spec_id = '4';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '2';
        $wireColor->warehouse_product_spec_id = '4';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '2';
        $wireColor->warehouse_product_spec_id = '4';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '3';
        $wireColor->warehouse_product_spec_id = '5';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '3';
        $wireColor->warehouse_product_spec_id = '6';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '3';
        $wireColor->warehouse_product_spec_id = '6';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '3';
        $wireColor->warehouse_product_spec_id = '6';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '4';
        $wireColor->warehouse_product_spec_id = '2';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '4';
        $wireColor->warehouse_product_spec_id = '3';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '4';
        $wireColor->warehouse_product_spec_id = '3';
        $wireColor->save();

        $wireColor = new SampleArticleColor();
        $wireColor->sample_articles_wire_id = '4';
        $wireColor->warehouse_product_spec_id = '3';
        $wireColor->save();
    }
}
