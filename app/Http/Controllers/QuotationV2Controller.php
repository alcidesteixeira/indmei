<?php

namespace App\Http\Controllers;

use App\Client;
use App\Quotation;
use App\QuotationV2;
use App\QuotationV2Spec;
use App\SampleArticle;
use App\SampleArticleColor;
use App\WarehouseProduct;
use App\WarehouseProductSpec;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
        dd($request->all());

        $quotation = new QuotationV2();
        $quotation->name = $request->reference;
        $quotation->reference = $request->reference;
        $quotation->client = $request->client;
        $quotation->date = $request->date;
        $quotation->defect_percentage = $request->defect;
        $quotation->company_cost_percentage = $request->company_cost;
        $quotation->comission_percentage = $request->comission;
        $quotation->transportation_percentage = $request->transportation;
        $quotation->extra_percentage = $request->extra1;
        $quotation->extra_2_percentage = $request->extra2;
        $quotation->client_price = $request->client_price;
        //Store Image Updated
        $file = $request->file('image_url');
        if($file) {
            $filename = 'quotations/' . explode('.', $file->getClientOriginalName())[0] . '-' . Carbon::now('Europe/London')->format('YmdHis') . '.jpg';
            Storage::disk('public')->put($filename, File::get($file));
            $quotation->product_image = $filename;
        }
        //End Store Image Upload
        $quotation->save();

        for($i = 0; $i < 12; $i++) {
            $is_custom = 'is_custom_1_' . $i;
            $sample_article_1 = 'sample_article_1_' . $i;
            $sample_article_2 = 'sample_article_2_' . $i;
            $price_custom = 'price_custom_' . $i;
            $price_list = 'price_list_' . $i;
            $percentage1 = 'percentage_1_' . $i;
            $percentage2 = 'percentage_2_' . $i;
            $kgs = 'kgs_' . $i;
            $total = 'total' . $i;
            $material = $request->$is_custom == 1 ? $request->$sample_article_2 : $request->$sample_article_1;
            $price = $request->$is_custom == 1 ? $request->$price_custom : $request->$price_list;

            $quotation_spec = new QuotationV2Spec();
            $quotation_spec->quotation_v2_id = $quotation->id;
            $quotation_spec->material = $material;
            $quotation_spec->manual_percentage = $request->$percentage1;
            $quotation_spec->kgs = $request->$kgs;
            $quotation_spec->price = $request->$price;
            $quotation_spec->total = $request->$total;
            $quotation_spec->save();
        }

        flash('Orçamento criado com sucesso!')->success();

        return redirect()->action('QuotationV2Controller@index');

    }

    public function priceUpdate($id)
    {
        $price = WarehouseProductSpec::find($id);
        $price = $price->cost;

        return $price;
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
