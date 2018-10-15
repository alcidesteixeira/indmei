<?php

namespace App\Http\Controllers;

use App\Client;
use App\OrderProduction;
use App\WarehouseProductSpec;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //Filtro inicial por Dia
        $filter = "Dia";

        //Obter Datas
        $start_date = Carbon::today()->startOfMonth();
        $end_date = Carbon::now();
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($start_date, $interval ,$end_date);
        $label = [];
        foreach($daterange as $date){
            $date = $date->format("Y-m-d");
            array_push($label,$date);
        }
        $start_date = $start_date->format('Y-m-d');
        $end_date = $end_date->format('Y-m-d');

        //Obter dados para gráfico 1
        $socksArr = OrderProduction::where('created_at', '>', $start_date)
            ->selectRaw('sum(value) Sum')
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('Date')
            ->get()
            ->toArray();
        $socks = [];
        foreach($socksArr as $sock) {
            $socks[$sock['date']] = $sock['Sum'];
        }

        //Obter dados para gráfico 2
        $stockSpendings = DB::table('warehouse_products_history')
            ->leftJoin('warehouse_product_specs', 'warehouse_products_history.warehouse_product_spec_id', '=', 'warehouse_product_specs.id')
            ->leftJoin('warehouse_products', 'warehouse_product_specs.warehouse_product_id', '=', 'warehouse_products.id')
            ->select('warehouse_products.reference', 'warehouse_product_specs.color')
            ->selectRaw('sum(weight) as Sum')
//            ->selectRaw('DATE(warehouse_products_history.created_at) as date')
//            ->where('warehouse_products_history.created_at', '>', $start_date)
            ->where('inout', 'OUT_GROSS')
            ->groupBy('warehouse_product_specs.color')
            ->get()
            ->toArray();

        //dd($stockSpendings);

        //Obter dados para gráfico 3
        $socksPerClient = DB::table('order_productions')
            ->leftJoin('orders', 'order_productions.order_id', '=', 'orders.id')
            ->leftJoin('clients', 'orders.client_id', '=', 'clients.id')
            ->select('orders.client_id', 'clients.client', 'order_productions.value')
            ->selectRaw('sum(value) as Sum')
            ->groupBy('clients.client')
            ->get()
            ->toArray();

        //Obter dados para gráfico 4
        //Primeiro obter quais os clientes existentes
        $clientes = Client::all()
            ->toArray();

        $i = 0;
        $g4Data = [];
        //Para cada cliente, obter o valor enviado no orçamento, por dia, para os clientes
        foreach($clientes as $client) {
            $quotPerDate = [];

            $clientsData = DB::table('clients')
                ->leftJoin('orders', 'clients.id', '=', 'orders.client_id')
                ->leftJoin('quotations', 'orders.id', '=', 'quotations.order_id')
                ->select('quotations.value_sent')
                ->selectRaw('DATE(quotations.created_at) as date')
                ->selectRaw('sum(quotations.value_sent) as total_value')
                ->where('clients.id', $client['id'])
                ->groupBy('date')
                ->pluck('total_value', 'date');

            //dd($clientsData);
            //Obter um array que possibilitasse o incremento do valor ao longo dos dias.
            $prev = 0;
            foreach($label as $date) {
                $quotPerDate [$date] = @$clientsData[$date] ? $clientsData[$date]+$prev : $prev;
                $prev = @$clientsData[$date] ? $clientsData[$date]+$prev : $prev;
            }
            //dd($quotPerDate);

            $g4Data[$i] = $quotPerDate;
            $i++;
        }

        return view('stats.index', compact('start_date', 'end_date', 'filter', 'socks', 'label', 'stockSpendings', 'socksPerClient', 'clientes', 'g4Data'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        if($request->filter == 'Dia') {
            $filterDB = 'date';
            $format = 'Y-m-d';
            $intSpec = 'P1D';
        }
        else if($request->filter == 'Mês') {
            $filterDB = 'month';
            $format = 'Y-m';
            $intSpec = 'P1M';
        }
        else{
            $filterDB = 'year';
            $format = 'Y';
            $intSpec = 'P1Y';
        }


        $start_date = Carbon::create(
            substr($request->start_date, 0, 4),
            substr($request->start_date, 5, 2),
            substr($request->start_date, 8, 2),
            0,
            0,
            0
        );
        $end_date = Carbon::create(
            substr($request->end_date, 0, 4),
            substr($request->end_date, 5, 2),
            substr($request->end_date, 8, 2),
            23,
            59,
            59
        );
//        dd($end_date);
        $interval = new DateInterval($intSpec);
        $daterange = new DatePeriod($start_date, $interval ,$end_date);
        $label = [];

        foreach($daterange as $date){
            $date = $date->format($format);
            array_push($label,$date);
        }

        $start_date = $start_date->format('Y-m-d');
        $end_date = $end_date->format('Y-m-d');

        //Grafico 1
        $socksArr = OrderProduction::where('created_at', '>', $start_date)
            ->selectRaw('sum(value) Sum')
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('LEFT(created_at , 7) as month')
            ->selectRaw('YEAR(created_at) as year')
            ->groupBy($filterDB)
            ->get()
            ->toArray();

        //dd($socksArr);
        $socks = [];
        foreach($socksArr as $sock) {
            $socks[$sock[$filterDB]] = $sock['Sum'];
        }

        $filter = $request->filter;


        //Obter dados para gráfico 2
        $stockSpendings = DB::table('warehouse_products_history')
            ->leftJoin('warehouse_product_specs', 'warehouse_products_history.warehouse_product_spec_id', '=', 'warehouse_product_specs.id')
            ->leftJoin('warehouse_products', 'warehouse_product_specs.warehouse_product_id', '=', 'warehouse_products.id')
            ->select('warehouse_products.reference', 'warehouse_product_specs.color')
            ->selectRaw('sum(weight) as  Sum')
//            ->selectRaw('DATE(warehouse_products_history.created_at) as date')
//            ->where('warehouse_products_history.created_at', '>', $start_date)
            ->where('inout', 'OUT_GROSS')
            ->groupBy('warehouse_product_specs.color')
            ->get()
            ->toArray();

        //Obter dados para gráfico 3
        $socksPerClient = DB::table('order_productions')
            ->leftJoin('orders', 'order_productions.order_id', '=', 'orders.id')
            ->leftJoin('clients', 'orders.client_id', '=', 'clients.id')
            ->select('orders.client_id', 'clients.client', 'order_productions.value')
            ->selectRaw('sum(value) as Sum')
            ->groupBy('clients.client')
            ->get()
            ->toArray();

        //Obter dados para gráfico 4
        //Primeiro obter quais os clientes existentes
        $clientes = Client::all()
            ->toArray();

        $i = 0;
        $g4Data = [];
        //Para cada cliente, obter o valor enviado, por dia, para os clientes
        foreach($clientes as $client) {
            $quotPerDate = [];

            $clientsData = DB::table('clients')
                ->leftJoin('orders', 'clients.id', '=', 'orders.client_id')
                ->leftJoin('quotations', 'orders.id', '=', 'quotations.order_id')
                ->select('quotations.value_sent')
                ->selectRaw('DATE(quotations.created_at) as date')
                ->selectRaw('LEFT(quotations.created_at , 7) as month')
                ->selectRaw('YEAR(quotations.created_at) as year')
                ->selectRaw('sum(quotations.value_sent) as total_value')
                ->where('clients.id', $client['id'])
                ->groupBy($filterDB)
                ->pluck('total_value', $filterDB);

            //dd($clientsData);
            //Valor a partir do qual iniciará a contagem ao longo dos dias: se for um dia à frente do que deveria, pegar no valor inicial correto
            //dd(key($clientsData));
            $prev = 0;
            foreach($clientsData as $key => $val) {
                if ($key <= $label[0]) {
                    $prev += $val;
                }
                else {
                    $prev += 0;
                }
            }
            $filter !== 'Dia' ? $prev = 0 : $prev  = $prev;
            //dd($prev);
            //Obter um array que possibilitasse o incremento do valor ao longo dos dias.
            foreach($label as $date) {
                $quotPerDate [$date] = @$clientsData[$date] ? $clientsData[$date]+$prev : $prev;
                $prev = @$clientsData[$date] ? $clientsData[$date]+$prev : $prev;
            }
            //dd($quotPerDate);

            //Juntar os resultados de todos os clientes num só array
            $g4Data[$i] = $quotPerDate;
            $i++;
        }
        //dd($g4Data);

        return view('stats.index', compact('start_date', 'end_date', 'filter', 'socks', 'label', 'stockSpendings', 'socksPerClient', 'clientes', 'g4Data'));
    }

}
