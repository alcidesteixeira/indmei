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
    public function pairsPerColorGross ($order_id) {
        //Selecionar as quantidades de pares de meias, POR COR, já executados

        $currentProduction = OrderProduction::where('order_id', $order_id)->get();
        $paresPorCor = [];

        foreach ($currentProduction as $newInsertion) {
            if(array_key_exists('cor'.$newInsertion->cor, $paresPorCor)) {
                $paresPorCor['cor' . $newInsertion->cor] = intval($paresPorCor['cor' . $newInsertion->cor]) + intval($newInsertion->value);
            } else {
                $paresPorCor['cor' . $newInsertion->cor] = intval($newInsertion->value);
            }
        }
        if(empty($currentProduction->first())) {
            $paresPorCor = ["cor1" => '0', "cor2" => '0', "cor3" => '0', "cor4" => '0'];
        }
        // * 0.97 / 2 uma vez que nos referimos a meias e não a pares
        foreach ($paresPorCor as $key => $par) {
            $paresPorCor[$key] = round($par * 0.97 / 2);
        }
        return ($paresPorCor);
        //FIM Selecionar as quantidades de pares de meias, POR COR, já executados
    }

    /**
     * Função que obtém as saídas totais de stock para o cálculo de stock LÍQUIDO e BRUTO
     */
    public function addRowToStockHistory ($request, $id) {

        //dd($request->all());

        $wires = $this->checkWireSpentInOnePair($request);
//        dd($wires);
        $clientName = Client::where('id', $request->client_id)->first()->client;
//        dd($clientName);
        $paresPorCorLiquido = $this->pairsPerColorLiquid($request);
//        dd($paresPorCorLiquido);
        $paresPorCorBruto = $this->pairsPerColorGross($id);
//        dd($paresPorCorBruto);

        //Delete das entradas antes de atualizar.
        DB::table('warehouse_products_history')
            ->where('description',  'Encomenda para o cliente: ' . $clientName . ', com o identificador: ' . $request->client_identifier)
            ->delete();

        //Obter imagem do produto
//        dd($request->sample_article_id);
        $orderImage = SampleArticle::where('id', $request->sample_article_id)->first()->image_url;
        //dd($orderImage);

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
                        'cost' => 0,
                        'receipt' => $orderImage,
                        'description' => 'Encomenda para o cliente: ' . $clientName . ', com o identificador: ' . $request->client_identifier,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    //Inserir valor bruto multiplicado pelos pares
                    DB::table('warehouse_products_history')->insert([
                        'warehouse_product_spec_id' => $wire->warehouse_product_spec_id,
                        'user_id' => Auth::id(),
                        'inout' => 'OUT_GROSS',
                        'weight' => $wire->grams * $paresPorCorBruto[$cor],
                        'cost' => 0,
                        'receipt' => $orderImage,
                        'description' => 'Encomenda para o cliente: ' . $clientName . ', com o identificador: ' . $request->client_identifier,
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
