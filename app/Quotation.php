<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    public function order()
    {
        return $this->hasOne('App\Order');
    }


    /**
     * Update the value of every OrderSample before starting the quotation
     */
    public function updateValueOfSamples ()
    {
        $sampleArticles = SampleArticle::all();

        $arrayColors = [];
        foreach ($sampleArticles as $sample) {
            for ($i = 0; $i < 17; $i ++) {
                $wireColors = $sample->sampleArticleWires()->get()->values()->get($i)->wireColors()->get()->values();
                foreach($wireColors as $key => $wire) {
                    $j = $i+1; $keyColor = $key+1;
                    $arrayColors['row-'.$j.'-color'.$keyColor] = $wire->warehouse_product_spec_id;
                }
                $arrayColors['row-'.$j.'-grams'] = $sample->sampleArticleWires()->get()->values()->get($i)->grams;
            }
            $arrayColors['rowCount'] = 17;

            $sampleCost = new SampleArticle();
            $sampleCost = $sampleCost->getValuePerSample($arrayColors);

            //Update SampleArticle with the correct values of wires used
            $sample->cost1 = round($sampleCost['cor1'], 2);
            $sample->cost2 = round($sampleCost['cor2'], 2);
            $sample->cost3 = round($sampleCost['cor3'], 2);
            $sample->cost4 = round($sampleCost['cor4'], 2);
            $sample->save();
        }


        return ($sampleCost);
    }
}
