<?php

namespace App\Http\Controllers;

use App\Client;
use App\Quotation;
use App\QuotationV2;
use App\SampleArticle;
use App\SampleArticleColor;
use App\WarehouseProduct;
use App\WarehouseProductSpec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuotationV2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->authorizeRoles(['1', '3', '4', '6', '7']);

        $quotationV2 = QuotationV2::all();

        return view('quotations_v2.list', compact('quotationV2'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quotationId = QuotationV2::orderBy('created_at', 'desc')->first();
        $quotationId =  $quotationId ? $quotationId->id +1 : '1';

        $clients = Client::all();

        $warehouseProductSpecs = WarehouseProductSpec::all();

        return view('quotations_v2.create', compact('warehouseProductSpecs', 'quotationId', 'clients', 'sampleArticle'));
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
