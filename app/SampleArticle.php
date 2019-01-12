<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SampleArticle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference', 'description', 'status_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function sampleArticleStatus()
    {
        return $this->belongsTo('App\OrderStatus');
    }

    public function sampleArticleWires()
    {
        return $this->hasMany('App\SampleArticlesWire');
    }

    public function sampleArticleGuiafio()
    {
        return $this->belongsTo('App\SampleArticleGuiafio');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    /**
     * recebe o id da amostra e calcula os valores para 4 cores
     * retorna um array com os 4 valores
     * o método corre ao criar ou ao fazer update de uma amostra
     */
    public function getValuePerSample ($sample) {

        //Obter valor de cada fio (por Kg)
        $wireCost = WarehouseProductSpec::all()->pluck('cost', 'id')->toArray();

        //Para cada cor, pegar na quantidade de fio e multiplicar pelo preço/100 (pois o fio está em gramas)
        //ir somando sempre ao valor anterior
        $valueCor = [
            'cor1' => 0,
            'cor2' => 0,
            'cor3' => 0,
            'cor4' => 0
        ];
//        dd($sample);
        for ($j = 1; $j <= 4; $j++) {
            for ($i = 1; $i <= $sample['rowCount']; $i++) {
                //Para o caso dos valores vazios, colocar aqui condição
                $keyForWireCost = intval(@$sample['row-'.$i.'-color'.$j])-1 == -1 ? 0 : intval(@$sample['row-'.$i.'-color'.$j]);
                $valueCor['cor'.$j] += $keyForWireCost == 0 ? '0' : intval(@$sample['row-'.$i.'-grams']) * $wireCost[$keyForWireCost] / 100;
            }
        }


        return $valueCor;
    }
}
