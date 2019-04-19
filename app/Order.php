<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function orderProductions()
    {
        return $this->hasMany('App\OrderProduction');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function status()
    {
        return $this->belongsTo('App\OrderStatus');
    }

    /**
     * Função que irá obter os gastos de cada fio para cada um dos pares
     */
    public function checkWireSpentInOnePair ($request) {
        //Add Row to Stock History with the order to subtract:
        $sampleArticleId = $request->sample_article_id;
        //SELECIONAR o total GASTO, POR FIO, numa amostra, por COR de UM PAR DE MEIAS:
        // multiplos de 4 -> somando 0 são cor 1;
        // multiplos de 4 -> somando 1 sao cor 2;
        // multiplos de 4 -> somando 2 sao cor 3;
        // multiplos de 4 -> somando 3 sao cor 4;
        $wires = DB::table('sample_articles_wires')
            ->select('sample_articles_wires.id', 'sample_article_id', 'warehouse_product_id', 'grams', 'a.sample_articles_wire_id', 'a.warehouse_product_spec_id')
            ->selectRaw('COUNT(sample_articles_wire_id) AS total_samples')
            ->selectRaw('MIN(a.id) AS minColorID')
            ->selectRaw(
                'CASE
                                WHEN MOD(a.id, 4) = 0 THEN 4
                                ELSE MOD(a.id, 4)
                            END as cor')
            ->leftJoin('sample_article_colors AS a', 'sample_articles_wires.id', '=', 'a.sample_articles_wire_id')
            ->where('sample_article_id', $sampleArticleId)
            ->groupBy('sample_articles_wires.id', 'a.id')
            ->orderBy('cor')
            ->orderBy('minColorID')
            ->get()
            ->toArray();
        //FIM SELECIONAR o total GASTO numa amostra, por COR de UM PAR DE MEIAS:
        return $wires;
    }

    /**
     * Função que retorna o total de pares para cada cor
     * Usada para o cálculo do STOCK LÍQUIDO: TOTAL MENOS AS ENCOMENDAS CRIADAS
     */
    public function pairsPerColorLiquid ($request) {
        //Selecionar as quantidades de pares de meias, POR COR
//        dd($request->all());
        $paresPorCor = [
            'cor1' => $request->tamanho11+$request->tamanho12+$request->tamanho13+$request->tamanho14,
            'cor2' => $request->tamanho21+$request->tamanho22+$request->tamanho23+$request->tamanho24,
            'cor3' => $request->tamanho31+$request->tamanho32+$request->tamanho33+$request->tamanho34,
            'cor4' => $request->tamanho41+$request->tamanho42+$request->tamanho43+$request->tamanho44
        ];
        //dd($paresPorCor);
        return $paresPorCor;
        //FIM Selecionar as quantidades de pares de meias, POR COR
    }

    /**
     * Função que retorna o total de pares para cada cor
     * Usada para o cálculo do STOCK BRUTO: TOTAL MENOS OS TOTAIS DE ENCOMENDAS JÁ EXECUTADOS PELOS OPERADORES
     */
    public function pairsPerColorGross ($order_id, $sample_article_id) {
        //Selecionar as quantidades de pares de meias, POR COR, já executados


        $recentDate = OrderProduction::groupBy('created_at')->orderBy('created_at', 'desc')->first();
        $currentProduction = OrderProduction::where('order_id', $order_id)->where('sample_article_id', $sample_article_id)->where('created_at', $recentDate->created_at)->get();
        $paresPorCor = [];

        foreach ($currentProduction as $newInsertion) {
            if(array_key_exists('cor'.$newInsertion->cor, $paresPorCor)) {
                $paresPorCor['cor' . $newInsertion->cor] = intval($paresPorCor['cor' . $newInsertion->cor]) + intval($newInsertion->value);
            } else {
                $paresPorCor['cor' . $newInsertion->cor] = intval($newInsertion->value);
            }
        }

        //fazer verificação para as 4 cores: caso não tenha, coloca valor a zero
        if(count($paresPorCor) !== 4) {
            for($i = 1; $i <=4; $i ++) {
                if (!array_key_exists('cor'.$i, $paresPorCor)) {
                    $paresPorCor['cor'.$i] = '0';
                }
            }
        }

        // * 0.97 / 2 uma vez que nos referimos a meias e não a pares
        foreach ($paresPorCor as $key => $par) {
            $paresPorCor[$key] = round($par / 1.03 / 2);
        }

        //dd($paresPorCor);
        return ($paresPorCor);
        //FIM Selecionar as quantidades de pares de meias, POR COR, já executados
    }

    /**
     * Função que obtém as saídas totais de stock para o cálculo de stock LÍQUIDO e BRUTO
     */
    public function addRowToStockHistory ($request, $id) {

        //Request da Order e Id da Order
        //dd($request->all());

        $wires = $this->checkWireSpentInOnePair($request);
        $clientName = Client::where('id', $request->client_id)->first()->client;
        $paresPorCorLiquido = $this->pairsPerColorLiquid($request);
        $paresPorCorBruto = $this->pairsPerColorGross($id, $request->sample_articl_id);


        $deletedRows = DB::table('warehouse_products_history')
            ->where('description',  'Encomenda para o cliente: ' . $clientName . ', com o identificador: ' . $request->client_identifier)
            ->groupBy('sample_article_id')
            ->get();
        //dd($deletedRows);

        //Se for diferente de vazio E FOR DIFERENTE DO VALOR ATUAL QUE VEM => apaga os resultados liquid e mantém os gross com updated de gross para GROSS_EXPIRED
        //vai fazer com que depois, se conte tal como os IN mas negativos
        foreach($deletedRows as $row) {
            if($row->sample_article_id !== $request->sample_article_id && $row->sample_article_id !== '') {
                //apagar liquidos
                DB::table('warehouse_products_history')
                    ->where('description',  'Encomenda para o cliente: ' . $clientName . ', com o identificador: ' . $request->client_identifier)
                    ->where('sample_article_id', $row->sample_article_id)
                    ->where('inout', 'OUT_LIQUID')
                    ->delete();
                //update gross expired
                DB::table('warehouse_products_history')
                    ->where('description',  'Encomenda para o cliente: ' . $clientName . ', com o identificador: ' . $request->client_identifier)
                    ->where('sample_article_id', $row->sample_article_id)
                    ->where('inout', 'OUT_GROSS')
                    ->update(['inout' => 'OUT_EXPIRED', 'description' => 'Amostra alterada.']);
            }
            if($row->sample_article_id == '') {
                DB::table('warehouse_products_history')
                    ->where('description',  'Encomenda para o cliente: ' . $clientName . ', com o identificador: ' . $request->client_identifier)
                    ->where('sample_article_id', '')
                    ->delete();
            }
            if($row->sample_article_id == $request->sample_article_id) {
                DB::table('warehouse_products_history')
                    ->where('description',  'Encomenda para o cliente: ' . $clientName . ', com o identificador: ' . $request->client_identifier)
                    ->where('sample_article_id', $request->sample_article_id)
                    ->delete();
            }
        }


        //Obter imagem do produto
        $orderImage = SampleArticle::where('id', $request->sample_article_id)->first()->image_url;

        //Editar para conter historico de liquid weight e gross weight!!!!
        //Adicionar as quantidades de fio, dependendo das meias selecionadas: stock
        for($i = 1; $i <= 4; $i++) {
            $cor = 'cor'.$i;
            foreach ($wires as $wire) {
                if($wire->cor == $i && $wire->grams !== '0') {
                    //Inserir valor liquido multiplicado pelos pares
                    DB::table('warehouse_products_history')->insert([
                        'warehouse_product_spec_id' => $wire->warehouse_product_spec_id,
                        'user_id' => Auth::id(),
                        'inout' => 'OUT_LIQUID',
                        'weight' => $wire->grams * $paresPorCorLiquido[$cor],
                        'cost' => 'N/A',
                        'receipt' => $orderImage,
                        'description' => 'Encomenda para o cliente: ' . $clientName . ', com o identificador: ' . $request->client_identifier,
                        'order_id' => $id,
                        'sample_article_id' => $request->sample_article_id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    //Inserir valor bruto multiplicado pelos pares
                    DB::table('warehouse_products_history')->insert([
                        'warehouse_product_spec_id' => $wire->warehouse_product_spec_id,
                        'user_id' => Auth::id(),
                        'inout' => 'OUT_GROSS',
                        'weight' => $wire->grams * $paresPorCorBruto[$cor],
                        'cost' => 'N/A',
                        'receipt' => $orderImage,
                        'description' => 'Encomenda para o cliente: ' . $clientName . ', com o identificador: ' . $request->client_identifier,
                        'order_id' => $id,
                        'sample_article_id' => $request->sample_article_id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
        //END - Add Row to Stock History with the order to subtract
        return $clientName;
    }


}
