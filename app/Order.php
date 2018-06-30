<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function sampleArticle()
    {
        return $this->belongsTo('App\SampleArticle');
    }

    public function quotation()
    {
        return $this->hasOne('App\Quotation');
    }

    public function orderFiles()
    {
        return $this->hasMany('App\OrderFile');
    }

}
