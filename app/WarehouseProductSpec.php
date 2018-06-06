<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseProductSpec extends Model
{
    protected $fillable = [
        'warehouse_product_id', 'color', 'weight',
    ];
    //This class exists because I want to save the id of the wire when creating a sample article. If it didn't existed, I would have to save several ids

    public function product ()
    {
        return $this->belongsTo('App\WarehouseProduct','warehouse_product_id', 'id');
    }
}
