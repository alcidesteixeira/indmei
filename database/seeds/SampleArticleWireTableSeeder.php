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
        $role_admin->step = 'G1';
        $role_admin->grams = '2';
        $role_admin->wire_ref = 'e22pa78/1';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step = 'G2';
        $role_admin->grams = '1';
        $role_admin->wire_ref = 'nylom70/1';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step = 'G3';
        $role_admin->grams = '6';
        $role_admin->wire_ref = 'algodao20/1';
        $role_admin->save();

        $role_admin = new SampleArticlesWire();
        $role_admin->sample_article_id = '1';
        $role_admin->step = 'G4';
        $role_admin->grams = '16';
        $role_admin->wire_ref = 'nylom70/1';
        $role_admin->save();

    }
}
