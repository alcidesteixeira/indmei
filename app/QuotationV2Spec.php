<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationV2Spec extends Model
{
    public function quotationv2()
    {
        return $this->hasOne('App\QuotationV2');
    }
}
