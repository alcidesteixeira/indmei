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

        $orders = Order::all();
        //IDS da encomenda
        $order_ids = [];
        foreach ($orders as $order) {
            array_push($order_ids, $order->id);
        }

        //IDS do histórico
        $ids_t_c = [];
        $ids_to_compare = DB::table('warehouse_products_history')
            ->select('order_id')
            ->where('order_id', '<>', 0)
            ->groupBy('order_id')
            ->get()->toArray();

        foreach($ids_to_compare as $id) {
            array_push($ids_t_c, $id->order_id);
        }

        foreach($ids_t_c as $id) {

            //Se não existir esta descrição, apaga histórico porque significa que a encomenda foi apagada
            if(!in_array($id, $order_ids)) {
//                echo "entra";
                DB::table('warehouse_products_history')
                ->where('order_id', $id)
                ->delete();
            }
        }

        //Para cada produto, precorre o array de histórico, e calcula o valor:
        //Se o valor de histórico tiver IN, soma; Se o valor histórico tiver OUT, subtrai.
        //Stock Líquido -> valor em stock menos o q foi associado para cada encomenda.
        //Stock Bruto -> valor em stock no momento -> corresponde ao stock menos o valor utilizado pelos operadores todos os dias.
        foreach($products as $product) {
            $total_liquid = $total_bruto = $total_to_do = 0;
            $cost = 0;
//dd($history);
            foreach ($history as $key => $val) {
                if ($val->warehouse_product_spec_id == $product->id) {
                    if($val->inout == 'IN') {
                        $total_liquid += $val->weight;
                        $cost = $val->cost;
                        $total_bruto += $val->weight;
                    }
                    if($val->inout == 'OUT_EXPIRED') {
                        $total_liquid -= $val->weight;
                        $total_bruto -= $val->weight;
                    }

                    //Valida se a encomenda já está terminada ou não:
                    //Se sim: o valor de liquido vai ser obtido através do bruto -> têm de ser iguais no final
                    //Se não: vai reduzindo no líquido normalmente, pois este tem de ser menor ou igual ao stock bruto
                    if($val->order_id !== 0) {
                        $status = Order::where('id', $val->order_id)->first();
                    }
                    if(isset($status) && ($status->status_id == '7' || $status->status_id == '1' || $status->status_id == '8')) {
                        //Cálculo de stock bruto: total menos o que já foi produzido, ou seja, o que existe efectivamente no armazém
                        if ($val->inout == 'OUT_GROSS') {
                            $total_bruto -= $val->weight;
                            $total_liquid -= $val->weight;
                        }
                        if ($status->status_id == '1') {
                            $total_to_do += $val->weight;
                        }
                    }
                    else {
                        //Cálculo de stock liquido: total menos as encomendas criadas
                        if ($val->inout == 'OUT_LIQUID') {
                            $total_liquid -= $val->weight;
                        }

                        //Cálculo de stock bruto: total menos o que já foi produzido, ou seja, o que existe efectivamente no armazém
                        if ($val->inout == 'OUT_GROSS') {
                            $total_bruto -= $val->weight;
                        }
                    }


                }
            }
            //Cálculo de stock bruto: total menos o que já foi produzido, ou seja, o que existe efectivamente no armazém
//            foreach ($history as $key => $val) {
//                if ($val->warehouse_product_spec_id == $product->id) {
//                    if($val->inout == 'IN') {
//                        $total_bruto += $val->weight;
//                    }
//
//                    if ($val->inout == 'OUT_GROSS') {
//                        $total_bruto -= $val->weight;
//                    }
//                }
//            }
            //Apenas atualiza os valores de stock que sofreram alterações
            $currentValsStored = WarehouseProductSpec::where('id', $product->id)->first();
            if(strcmp($currentValsStored->liquid_weight, $total_liquid) ||
                strcmp($currentValsStored->gross_weight, $total_bruto) ||
                strcmp($currentValsStored->cost, $cost) ||
                strcmp($currentValsStored->to_do_weight, $total_to_do)) {

                WarehouseProductSpec::where('id', $product->id)
                    ->update([
                        'liquid_weight' => $total_liquid,
                        'gross_weight' => $total_bruto,
                        'to_do_weight' => $total_to_do,
                        'cost' => $cost
                    ]);
            }
        }
        return "done";
    }

}
