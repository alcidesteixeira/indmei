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
        //Stock Líquido -> valor em stock menos o q foi associado para cada encomenda.
        //Stock Bruto -> valor em stock no momento -> corresponde ao stock menos o valor utilizado pelos operadores todos os dias.
        foreach($products as $product) {
            $total_liquid = $total_bruto = 0;
            $cost = 0;
            //Cálculo de stock liquido: total menos as encomendas criadas
            foreach ($history as $key => $val) {
                if ($val->warehouse_product_spec_id == $product->id) {
                    if($val->inout == 'IN') {
                        $total_liquid += $val->weight;
                        $cost = $val->cost;
                    }
                    elseif ($val->inout == 'OUT_LIQUID') {
                        $total_liquid -= $val->weight;
                    }
                }
            }
            //Cálculo de stock bruto: total menos o que já foi produzido, ou seja, o que existe efectivamente no armazém
            foreach ($history as $key => $val) {
                if ($val->warehouse_product_spec_id == $product->id) {
                    if($val->inout == 'IN') {
                        $total_bruto += $val->weight;
                    }
                    elseif ($val->inout == 'OUT_GROSS') {
                        $total_bruto -= $val->weight;
                    }
                }
            }

            WarehouseProductSpec::where('id', $product->id)
                ->update([
                    'liquid_weight' => $total_liquid,
                    'gross_weight' => $total_bruto,
                    'cost' => $cost
                ]);
        }
        return "done";
    }

}
