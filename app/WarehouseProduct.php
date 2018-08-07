<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WarehouseProduct extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function sampleArticleWires()
    {
        return $this->hasMany('App\SampleArticleWire');
    }

    public function warehouseProductSpecs()
    {
        return $this->hasMany('App\WarehouseProductSpec');
    }

    public function getProducts () {

        $products = WarehouseProduct::all();

        return $products;
    }

    /**
     * This function will run every time the user enters the Stock List
     **/
    public function updateStocks () {

        $history = DB::table('warehouse_products_history')
            ->get()->toArray();

        $products = WarehouseProductSpec::all();

        //Para cada produto, precorre o array de histórico, e calcula o valor:
        //Se o valor de histórico tiver IN, soma; Se o valor histórico tiver OUT, subtrai.
        //Stock Líquido -> valor em stock menos o q foi designado para cada encomenda.
        //Stock Bruto -> valor em stock no momento -> corresponde ao stock menos o valor em utilização atualmente para as encomendas.
        foreach($products as $product) {
            $total_liquid = 0;
            $cost = 0;
            foreach ($history as $key => $val) {
                if ($val->warehouse_product_spec_id == $product->id) {
                    if($val->inout == 'IN') {
                        $total_liquid += $val->weight;
                        $cost = $val->cost;
                    }
                    else {
                        $total_liquid -= $val->weight;
                    }
                }
            }
            WarehouseProductSpec::where('id', $product->id)
                ->update(['liquid_weight' => $total_liquid, 'cost' => $cost]);
        }
        return "done";
    }

}
