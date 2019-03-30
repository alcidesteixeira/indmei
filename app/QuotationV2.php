<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationV2 extends Model
{
    public function specs()
    {
        return $this->hasMany('App\QuotationV2Spec');
    }

    public function sampleArticle()
    {
        return $this->hasOne('App\SampleArticle');
    }
}
