<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SampleArticlesWire extends Model
{
    // Since the wire is defined by its color, it would be enough to call it by its color id on the warehouse, so the
    // warehouse_product_id is a bit overkill. Still, it is used to understand which wire is selected

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
