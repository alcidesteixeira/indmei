<?php

use App\SampleArticleGuiafio;
use Illuminate\Database\Seeder;

class SampleArticleGuiafioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wireColor = new SampleArticleGuiafio();
        $wireColor->description = 'crava';
        $wireColor->save();

        $wireColor = new SampleArticleGuiafio();
        $wireColor->description = 'felpa';
        $wireColor->save();

        $wireColor = new SampleArticleGuiafio();
        $wireColor->description = 'vaniza';
        $wireColor->save();

        $wireColor = new SampleArticleGuiafio();
        $wireColor->description = 'fundo';
        $wireColor->save();

        $wireColor = new SampleArticleGuiafio();
        $wireColor->description = 'punho';
        $wireColor->save();

        $wireColor = new SampleArticleGuiafio();
        $wireColor->description = 'risca';
        $wireColor->save();

        $wireColor = new SampleArticleGuiafio();
        $wireColor->description = 'tamanho';
        $wireColor->save();

        $wireColor = new SampleArticleGuiafio();
        $wireColor->description = 'letras perna';
        $wireColor->save();

        $wireColor = new SampleArticleGuiafio();
        $wireColor->description = '';
        $wireColor->save();

    }
}
