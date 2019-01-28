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
        $orderDescs = [];
        foreach ($orders as $order) {
            $clientName = Client::where('id', $order->client_id)->first()->client;
            array_push($orderDescs, 'Encomenda para o cliente: ' . $clientName . ', com o identificador: ' . $order->client_identifier);
        }

        $descriptions = [];
        $historyDescriptions = DB::table('warehouse_products_history')
            ->select('description')
            ->where('inout', '<>', 'IN')
            ->groupBy('description')
            ->get();
        foreach($historyDescriptions as $desc) {
            array_push($descriptions, $desc->description);

            //Se não existir esta descrição, apaga histórico porque significa que a encomenda foi apagada
            if(!in_array($desc->description, $orderDescs)) {
                DB::table('warehouse_products_history')
                ->where('description', $desc->description)
                ->delete();
            }
        }

        //Para cada produto, precorre o array de histórico, e calcula o valor:
        //Se o valor de histórico tiver IN, soma; Se o valor histórico tiver OUT, subtrai.
        //Stock Líquido -> valor em stock menos o q foi associado para cada encomenda.
        //Stock Bruto -> valor em stock no momento -> corresponde ao stock menos o valor utilizado pelos operadores todos os dias.
        foreach($products as $product) {
            $total_liquid = $total_bruto = 0;
            $cost = 0;

            foreach ($history as $key => $val) {
                if ($val->warehouse_product_spec_id == $product->id) {
                    if($val->inout == 'IN') {
                        $total_liquid += $val->weight;
                        $cost = $val->cost;
                        $total_bruto += $val->weight;
                    }

                    //Valida se a encomenda já está terminada ou não:
                    //Se sim: o valor de liquido vai ser obtido através do bruto -> têm de ser iguais no final
                    //Se não: vai reduzindo no líquido normalmente, pois este tem de ser menor ou igual ao stock bruto
                    preg_match('/\d\d\d\d-\d/', $val->description, $m);
                    $order_id = $m[0] ?? '';
                    if($order_id !== '') {
                        $status = Order::where('client_identifier', $order_id)->first()->status_id;
                        if($status == '7') {
                            //Cálculo de stock bruto: total menos o que já foi produzido, ou seja, o que existe efectivamente no armazém
                            if ($val->inout == 'OUT_GROSS') {
                                $total_bruto -= $val->weight;
                                $total_liquid -= $val->weight;
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
                strcmp($currentValsStored->cost, $cost)) {

                WarehouseProductSpec::where('id', $product->id)
                    ->update([
                        'liquid_weight' => $total_liquid,
                        'gross_weight' => $total_bruto,
                        'cost' => $cost
                    ]);
            }
        }
        return "done";
    }

}
