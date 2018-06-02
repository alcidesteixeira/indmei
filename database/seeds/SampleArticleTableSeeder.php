<?php

use App\SampleArticle;
use Illuminate\Database\Seeder;

class SampleArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sampleArticle = new SampleArticle();
        $sampleArticle->user_id = '1';
        $sampleArticle->reference = '5866';
        $sampleArticle->description = 'Meia de trail';
        $sampleArticle->image_url = 'meia_trai.jpg';
        $sampleArticle->status_id = '1';
        $sampleArticle->pe = '33';
        $sampleArticle->perna = '40';
        $sampleArticle->punho = '9,5';
        $sampleArticle->malha = '23';
        $sampleArticle->maq = '156';
        $sampleArticle->forma = '10,5';
        $sampleArticle->save();
    }
}
