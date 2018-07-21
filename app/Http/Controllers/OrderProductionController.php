<?php

namespace App\Http\Controllers;

use App\Order;
use App\SampleArticleGuiafio;
use App\SampleArticleStep;
use App\WarehouseProduct;
use App\WarehouseProductSpec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        Auth::user()->authorizeRoles(['1', '4', '6']);

        $order = Order::find($id);

        $guiafios = SampleArticleGuiafio::all();
        $steps = SampleArticleStep::all();
        $warehouseProducts = WarehouseProduct::all();
        $warehouseProductSpecs = WarehouseProductSpec::all();

        //dd(@$order->sampleArticle->sampleArticleWires()->get()->values()->get(13)->warehouseProduct);

        return view('orders.production.create', compact('order', 'guiafios', 'steps', 'warehouseProducts', 'warehouseProductSpecs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
