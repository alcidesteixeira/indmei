<?php

use App\SampleArticlesWire;
use Illuminate\Database\Seeder;

class SampleArticleWireTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step_id = '1';
        $role_admin->guiafios_id = '1';
        $role_admin->grams = '2';
        $role_admin->warehouse_product_id = '1';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step_id = '2';
        $role_admin->guiafios_id = '1';
        $role_admin->grams = '1';
        $role_admin->warehouse_product_id = '2';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step_id = '3';
        $role_admin->guiafios_id = '1';
        $role_admin->grams = '6';
        $role_admin->warehouse_product_id = '3';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step_id = '4';
        $role_admin->guiafios_id = '1';
        $role_admin->grams = '16';
        $role_admin->warehouse_product_id = '2';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step_id = '5';
        $role_admin->guiafios_id = '1';
        $role_admin->grams = '16';
        $role_admin->warehouse_product_id = '2';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step_id = '6';
        $role_admin->guiafios_id = '1';
        $role_admin->grams = '16';
        $role_admin->warehouse_product_id = '2';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step_id = '7';
        $role_admin->guiafios_id = '1';
        $role_admin->grams = '16';
        $role_admin->warehouse_product_id = '2';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step_id = '8';
        $role_admin->guiafios_id = '1';
        $role_admin->grams = '16';
        $role_admin->warehouse_product_id = '2';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step_id = '9';
        $role_admin->guiafios_id = '1';
        $role_admin->grams = '16';
        $role_admin->warehouse_product_id = '2';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step_id = '10';
        $role_admin->guiafios_id = '1';
        $role_admin->grams = '16';
        $role_admin->warehouse_product_id = '2';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step_id = '11';
        $role_admin->guiafios_id = '1';
        $role_admin->grams = '16';
        $role_admin->warehouse_product_id = '2';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step_id = '12';
        $role_admin->guiafios_id = '1';
        $role_admin->grams = '16';
        $role_admin->warehouse_product_id = '2';
        $role_admin->save();

    }
}
