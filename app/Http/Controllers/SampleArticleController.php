<?php

namespace App\Http\Controllers;

use App\SampleArticle;
use App\SampleArticleColor;
use App\SampleArticleStep;
use App\SampleArticlesWire;
use App\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SampleArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->authorizeRoles(['1', '3']);

        $sampleArticles = SampleArticle::all();

        return view('samples.list', compact('sampleArticles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->authorizeRoles(['1', '3']);

        $steps = SampleArticleStep::all();

        $warehouseProducts = WarehouseProduct::all();

        return view('samples.create', compact('steps', 'warehouseProducts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Auth::user()->authorizeRoles(['1', '3']);

        //dd($request->all());

        //Store on SampleArticle Class
        $sampleArticle= new SampleArticle();
        $sampleArticle->user_id = Auth::id();
        $sampleArticle->reference = $request->reference;
        $sampleArticle->description = $request->description;
        $sampleArticle->image_url = $request->image_url;
        $sampleArticle->sample_article_status_id = $request->status_id;
        $sampleArticle->pe = $request->pe;
        $sampleArticle->perna = $request->perna;
        $sampleArticle->punho = $request->punho;
        $sampleArticle->malha = $request->malha;
        $sampleArticle->maq = $request->maq;
        $sampleArticle->forma = $request->forma;
        $sampleArticle->save();

        //Store on SampleArticleWire Class
        //Run for each row of the table
        for($i = 1; $i <= $request->rowCount; $i++) {
            $step = 'row-'.$i.'-step';
            $grams = 'row-'.$i.'-grams';
            $reference = 'row-'.$i.'-reference';

            $wire = new sampleArticlesWire();
            $wire->sample_article_id = $sampleArticle->id;
            $wire->step_id = $request->$step;
            $wire->warehouse_product_id = $request->$reference;
            $wire->grams = $request->$grams;
            $wire->save();

            //Store on SampleArticleColor Class
            //Run for the different number of colors
            for($j = 1; $j <= $request->colorsCount; $j++) {
                $colorFromWarehouse = 'row-'.$i.'-color'.$j;
                $wireColor = new sampleArticleColor();
                $wireColor->sample_articles_wire_id = $wire->id;
                $wireColor->warehouse_product_spec_id = $request->$colorFromWarehouse;
                $wireColor->save();
            }
        }

        flash('Amostra de Artigo com a referência: '. $sampleArticle->reference . ', e descrição: '. $sampleArticle->description .' foi criada com sucesso!')->success();

        return redirect()->action('SampleArticleController@index');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Auth::user()->authorizeRoles(['1', '3']);

        $sampleArticle = SampleArticle::find($id);

        $steps = SampleArticleStep::all();

        $warehouseProducts = WarehouseProduct::all();

//        dd($sampleArticle->sampleArticleWires()->get()->values()->get(2)->warehouse_product_id);
        dd($sampleArticle->sampleArticleWires()->get()->values()->get(2)->warehouseProduct->warehouseProductSpecs()->get());

        return view('samples.create', compact('sampleArticle', 'steps', 'warehouseProducts', 'id'));
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
