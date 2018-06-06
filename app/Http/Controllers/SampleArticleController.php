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

        $warehouseFirstWireSpecs = WarehouseProduct::find(1)->warehouseProductSpecs()->get();

        return view('samples.create', compact('steps', 'warehouseProducts', 'warehouseFirstWireSpecs'));
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

//        dd($sampleArticle->sampleArticleWires()->get()->values()->get(2)->wireColors()->get()->values()->get(2)->warehouse_product_spec_id);
        //dd($sampleArticle->sampleArticleWires()->get()->values()->get(2)->warehouseProduct->warehouseProductSpecs()->get());

        return view('samples.create', compact('sampleArticle', 'steps', 'warehouseProducts', 'id'));
    }

    public function updateWireSpecs($id)
    {

        $warehousetWireSpecs = WarehouseProduct::find($id)->warehouseProductSpecs()->get();

        foreach($warehousetWireSpecs as $spec) {
            $warehousetWireSpecsArray [$spec->id] = $spec->color;
        }

        return ($warehousetWireSpecsArray);
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

        Auth::user()->authorizeRoles(['1', '3']);

        //Update SampleArticle Class
        $sampleArticle= SampleArticle::find($id);
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

        //dd($request->all());
        //dd($sampleArticle->sampleArticleWires()->get()->values()->get(0)->wireColors()->get()->values()->get(0)->warehouse_product_spec_id);

        //Store on SampleArticleWire Class
        //Run for each row of the table
        for($i = 1; $i <= $request->rowCount; $i++) {
            $step = 'row-'.$i.'-step';
            $grams = 'row-'.$i.'-grams';
            $reference = 'row-'.$i.'-reference';

            $wire = $sampleArticle->sampleArticleWires()->get()->values()->get($i-1);
            $wire->step_id = $request->$step;
            $wire->warehouse_product_id = $request->$reference;
            $wire->grams = $request->$grams;
            $wire->save();

            //Store on SampleArticleColor Class
            //Run for the different number of colors
            for($j = 1; $j <= $request->colorsCount; $j++) {
                $colorFromWarehouse = 'row-'.$i.'-color'.$j;
                $wireColor = $wire->wireColors()->get()->values()->get($j-1);
                $wireColor->warehouse_product_spec_id = $request->$colorFromWarehouse;
                $wireColor->save();
            }
        }

        flash('O Artigo com a referência: '. $sampleArticle->reference . ', e a descrição: '. $sampleArticle->description .' foi atualizado com sucesso!')->success();

        return redirect()->action('SampleArticleController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Auth::user()->authorizeRoles(['1', '3']);

        $sampleArticle = SampleArticle::find($id);

        //dd($sampleArticle->sampleArticleWires()->get()->pluck('id')->toArray());

        $isLastStatus = $sampleArticle->where('sample_article_status_id', '6')->first();

        //dd($isNotLastStatus);

        if($isLastStatus) {

            flash('Atenção! A Amostra de Artigo com a referência '. $sampleArticle->reference . ' não pode ser eliminado pois ainda não se encontra no estado "Em Distribuição"!
                <br> Altere o estado do Artigo para "Em distribuição" antes de poder apagar a Amostra de Artigo!')->error();

        }
        else {

            //Delete Wire Specs
            $wireColorsIds = $sampleArticle->sampleArticleWires()->get()->pluck('id')->toArray();
            SampleArticleColor::whereIn('sample_articles_wire_id', $wireColorsIds)->delete();
dump("apagou colors");
            //Delete wires
            SampleArticlesWire::where('sample_article_id', $id)->delete();
            dump("apagou wires");
            //Delete Sample Article
            $sampleArticle->delete();
            dump("apagou artigo");
            flash('O Artigo com a referência: '. $sampleArticle->reference . ', e a descrição: '. $sampleArticle->description .' foi eliminado com sucesso!')->success();
        }

        return redirect()->action('SampleArticleController@index');
    }
}
