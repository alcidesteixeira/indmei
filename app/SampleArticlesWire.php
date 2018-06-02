<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SampleArticlesWire extends Model
{
    public function sampleArticle()
    {
        return $this->belongsTo('App\SampleArticle');
    }

    public function warehouseProduct()
    {
        return $this->belongsTo('App\WarehouseProduct');
    }

    public function wireColors()
    {
        return $this->hasMany('App\SampleArticleColor');
    }

    public function step()
    {
        return $this->belongsTo('App\SampleArticleStep');
    }
}
