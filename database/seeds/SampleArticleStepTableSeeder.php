<?php

use App\SampleArticleStep;
use Illuminate\Database\Seeder;

class SampleArticleStepTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $step = new SampleArticleStep();
        $step->step = 'G1';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'G2';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'G3';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'G4';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'G5';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'G6';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'G7';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'G8';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'PUNHO';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'BR1';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'BR2';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'BR3';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'BR4';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'BR5';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'BR6';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'BR7';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = 'BR8';
        $step->save();

        $step = new SampleArticleStep();
        $step->step = '';
        $step->save();
    }
}
