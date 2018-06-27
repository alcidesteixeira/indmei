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
        $sampleArticle->sample_article_status_id = '1';
        $sampleArticle->tamanho1 = '35/38';
        $sampleArticle->pe1 = '33';
        $sampleArticle->perna1 = '40';
        $sampleArticle->punho1 = '9,5';
        $sampleArticle->malha1 = '23';
        $sampleArticle->maq1 = '156';
        $sampleArticle->forma1 = '10,5';
        $sampleArticle->tamanho2 = '39/42';
        $sampleArticle->pe2 = '33';
        $sampleArticle->perna2 = '40';
        $sampleArticle->punho2 = '9,5';
        $sampleArticle->malha2 = '23';
        $sampleArticle->maq2 = '156';
        $sampleArticle->forma2 = '10,5';
        $sampleArticle->tamanho3 = '43/46';
        $sampleArticle->pe3 = '33';
        $sampleArticle->perna3 = '40';
        $sampleArticle->punho3 = '9,5';
        $sampleArticle->malha3 = '23';
        $sampleArticle->maq3 = '156';
        $sampleArticle->forma3 = '10,5';
        $sampleArticle->tamanho4 = '47/50';
        $sampleArticle->pe4 = '33';
        $sampleArticle->perna4 = '40';
        $sampleArticle->punho4 = '9,5';
        $sampleArticle->malha4 = '23';
        $sampleArticle->maq4 = '156';
        $sampleArticle->forma4 = '10,5';
        $sampleArticle->save();
    }
}
