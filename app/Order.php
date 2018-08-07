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

    public function addRowToStockHistoty ($request) {
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
        //dd($wires);
        $clientName = Client::where('id', $request->client_id)->first()->client;

        //Selecionar as quantidades de pares de meias, POR COR
//        dd($request->all());
        $paresPorCor = [
            'cor1' => $request->tamanho11+$request->tamanho12+$request->tamanho13+$request->tamanho14,
            'cor2' => $request->tamanho21+$request->tamanho22+$request->tamanho23+$request->tamanho24,
            'cor3' => $request->tamanho31+$request->tamanho32+$request->tamanho33+$request->tamanho34,
            'cor4' => $request->tamanho41+$request->tamanho42+$request->tamanho43+$request->tamanho44
            ];
        //dd($paresPorCor);
        //FIM Selecionar as quantidades de pares de meias, POR COR

        //Delete das entradas antes de atualizar.
        DB::table('warehouse_products_history')
            ->where('description',  'Encomenda para o cliente: ' . $clientName . ', com o identificador: ' . $request->client_identifier)
            ->delete();

        //Editar para conter historico de liquid weight e gross weight!!!!
        //Adicionar as quantidades de fio, dependendo das meias selecionadas: stock
        for($i = 1; $i <= 4; $i++) {
            $cor = 'cor'.$i;
            foreach ($wires as $wire) {
                if($wire->cor == $i) {
                    DB::table('warehouse_products_history')->insert([
                        'warehouse_product_spec_id' => $wire->warehouse_product_spec_id,
                        'user_id' => Auth::id(),
                        'inout' => 'OUT',
                        'weight' => $wire->grams * $paresPorCor[$cor],
                        'cost' => 0,
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
