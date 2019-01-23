<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    protected $fillable = [
        'warehouse_product_spec_id', 'amount_requested', 'email_sent'
    ];

    public function warehouseProductSpec ()
    {
        return $this->belongsTo('App\WarehouseProductSpec');
    }

}
