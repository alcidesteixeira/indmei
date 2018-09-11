<?php

namespace App\Http\Controllers;

use App\OrderProduction;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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

        $filter = "Dia";

        return view('stats.index', compact('start_date', 'end_date', 'filter', 'socks', 'label'));
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
        //dd($request->all());
//        dd(substr($request->start_date, 8, 2));


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
            0,
            0,
            0
        );
        $interval = new DateInterval($intSpec);
        $daterange = new DatePeriod($start_date, $interval ,$end_date);
        $label = [];

        foreach($daterange as $date){
            $date = $date->format($format);
            array_push($label,$date);
        }

        $start_date = $start_date->format('Y-m-d');
        $end_date = $end_date->format('Y-m-d');

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

        return view('stats.index', compact('start_date', 'end_date', 'filter', 'socks', 'label'));
    }

}
