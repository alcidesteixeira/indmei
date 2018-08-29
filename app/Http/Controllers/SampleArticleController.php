<?php

namespace App\Http\Controllers;

use App\SampleArticle;
use App\SampleArticleColor;
use App\SampleArticleGuiafio;
use App\OrderStatus;
use App\SampleArticleStep;
use App\SampleArticlesWire;
use App\WarehouseProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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

        $guiafios = SampleArticleGuiafio::all();

        return view('samples.create', compact('steps', 'warehouseProducts', 'warehouseFirstWireSpecs', 'guiafios'));
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

        $sampleCost = new SampleArticle();
        $sampleCost = $sampleCost->getValuePerSample($request->all());

        //Store on SampleArticle Class
        $sampleArticle= new SampleArticle();
        $sampleArticle->user_id = Auth::id();
        $sampleArticle->reference = $request->reference;
        $sampleArticle->description = $request->description;
        //Store Image
        $file = $request->file('image_url');
        if($file) {
            $filename = 'sampleArticles/' . explode('.', $file->getClientOriginalName())[0] . '-' . Carbon::now('Europe/London')->format('YmdHis') . '.jpg';
            Storage::disk('public')->put($filename, File::get($file));
            $sampleArticle->image_url = $filename;
        }
        //End Store Image
        $sampleArticle->tamanho1 = $request->tamanho1;
        $sampleArticle->pe1 = $request->pe1;
        $sampleArticle->perna1 = $request->perna1;
        $sampleArticle->punho1 = $request->punho1;
        $sampleArticle->malha1 = $request->malha1;
        $sampleArticle->maq1 = $request->maq1;
        $sampleArticle->forma1 = $request->forma1;
        $sampleArticle->tamanho2 = $request->tamanho2;
        $sampleArticle->pe2 = $request->pe2;
        $sampleArticle->perna2 = $request->perna2;
        $sampleArticle->punho2 = $request->punho2;
        $sampleArticle->malha2 = $request->malha2;
        $sampleArticle->maq2 = $request->maq2;
        $sampleArticle->forma2 = $request->forma2;
        $sampleArticle->tamanho3 = $request->tamanho3;
        $sampleArticle->pe3 = $request->pe3;
        $sampleArticle->perna3 = $request->perna3;
        $sampleArticle->punho3 = $request->punho3;
        $sampleArticle->malha3 = $request->malha3;
        $sampleArticle->maq3 = $request->maq3;
        $sampleArticle->forma3 = $request->forma3;
        $sampleArticle->tamanho4 = $request->tamanho4;
        $sampleArticle->pe4 = $request->pe4;
        $sampleArticle->perna4 = $request->perna4;
        $sampleArticle->punho4 = $request->punho4;
        $sampleArticle->malha4 = $request->malha4;
        $sampleArticle->maq4 = $request->maq4;
        $sampleArticle->forma4 = $request->forma4;
        $sampleArticle->cost1 = $sampleCost['cor1'];
        $sampleArticle->cost2 = $sampleCost['cor2'];
        $sampleArticle->cost3 = $sampleCost['cor3'];
        $sampleArticle->cost4 = $sampleCost['cor4'];
        $sampleArticle->save();

        //Store on SampleArticleWire Class
        //Run for each row of the table
        for($i = 1; $i <= $request->rowCount; $i++) {
            $step = 'row-'.$i.'-step';
            $grams = 'row-'.$i.'-grams';
            $reference = 'row-'.$i.'-reference';
            $guiafios = 'row-'.$i.'-guiafios';

            $wire = new sampleArticlesWire();
            $wire->sample_article_id = $sampleArticle->id;
            $wire->step_id = $request->$step ? $request->$step : '0';
            $wire->warehouse_product_id = $request->$reference;
            $wire->guiafios_id = $request->$guiafios;
            $wire->grams = $request->$grams ? $request->$grams : '0';
            $wire->save();

            //Store on SampleArticleColor Class
            //Run for the different number of colors
            for($j = 1; $j < $request->colorsCount; $j++) {
                $colorFromWarehouse = 'row-'.$i.'-color'.$j;
                $wireColor = new sampleArticleColor();
                $wireColor->sample_articles_wire_id = $wire->id;
                $wireColor->warehouse_product_spec_id = $request->$colorFromWarehouse ? $request->$colorFromWarehouse : '0';
                $wireColor->save();
            }
        }

        flash('A Amostra de Artigo com a referência: "'. $sampleArticle->reference . '", e descrição: "'. $sampleArticle->description .'" foi criada com sucesso!')->success();

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

        $guiafios = SampleArticleGuiafio::all();

//        dd($sampleArticle->sampleArticleWires()->get()->values()->get(16)->guiafios_id);
//        dd($sampleArticle->sampleArticleWires()->get()->values()->get(16)->wireColors()->get());
//        dd($sampleArticle->sampleArticleWires()->get()->values()->get(16)->warehouseProduct->warehouseProductSpecs()->get());

        return view('samples.create', compact('sampleArticle', 'steps', 'warehouseProducts', 'guiafios', 'id'));
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

        $sampleCost = new SampleArticle();
        $sampleCost = $sampleCost->getValuePerSample($request->all());

        //Update SampleArticle Class
        $sampleArticle= SampleArticle::find($id);
        $sampleArticle->reference = $request->reference;
        $sampleArticle->description = $request->description;
        //Store Image Updated
        $file = $request->file('image_url');
        if($file) {
            $filename = 'sampleArticles/' . explode('.', $file->getClientOriginalName())[0] . '-' . Carbon::now('Europe/London')->format('YmdHis') . '.jpg';
            Storage::disk('public')->put($filename, File::get($file));
            $sampleArticle->image_url = $filename;
        }
        //End Store Image Upload
        $sampleArticle->tamanho1 = $request->tamanho1;
        $sampleArticle->pe1 = $request->pe1;
        $sampleArticle->perna1 = $request->perna1;
        $sampleArticle->punho1 = $request->punho1;
        $sampleArticle->malha1 = $request->malha1;
        $sampleArticle->maq1 = $request->maq1;
        $sampleArticle->forma1 = $request->forma1;
        $sampleArticle->tamanho2 = $request->tamanho2;
        $sampleArticle->pe2 = $request->pe2;
        $sampleArticle->perna2 = $request->perna2;
        $sampleArticle->punho2 = $request->punho2;
        $sampleArticle->malha2 = $request->malha2;
        $sampleArticle->maq2 = $request->maq2;
        $sampleArticle->forma2 = $request->forma2;
        $sampleArticle->tamanho3 = $request->tamanho3;
        $sampleArticle->pe3 = $request->pe3;
        $sampleArticle->perna3 = $request->perna3;
        $sampleArticle->punho3 = $request->punho3;
        $sampleArticle->malha3 = $request->malha3;
        $sampleArticle->maq3 = $request->maq3;
        $sampleArticle->forma3 = $request->forma3;
        $sampleArticle->tamanho4 = $request->tamanho4;
        $sampleArticle->pe4 = $request->pe4;
        $sampleArticle->perna4 = $request->perna4;
        $sampleArticle->punho4 = $request->punho4;
        $sampleArticle->malha4 = $request->malha4;
        $sampleArticle->maq4 = $request->maq4;
        $sampleArticle->forma4 = $request->forma4;
        $sampleArticle->cost1 = $sampleCost['cor1'];
        $sampleArticle->cost2 = $sampleCost['cor2'];
        $sampleArticle->cost3 = $sampleCost['cor3'];
        $sampleArticle->cost4 = $sampleCost['cor4'];
        $sampleArticle->save();

        //dd($request->all());
        //dd($sampleArticle->sampleArticleWires()->get()->values()->get(0)->wireColors()->get()->values()->get(0)->warehouse_product_spec_id);

        //Store on SampleArticleWire Class
        //Run for each row of the table
        for($i = 1; $i <= $request->rowCount; $i++) {
            $step = 'row-'.$i.'-step';
            $grams = 'row-'.$i.'-grams';
            $reference = 'row-'.$i.'-reference';
            $guiafios = 'row-'.$i.'-guiafios';

            $wire = $sampleArticle->sampleArticleWires()->get()->values()->get($i-1);
            $wire->step_id = $request->$step ? $request->$step : '0';
            $wire->warehouse_product_id = $request->$reference;
            $wire->guiafios_id = $request->$guiafios;
            $wire->grams = $request->$grams ? $request->$grams : '0';
            $wire->save();

            //Store on SampleArticleColor Class
            //Run for the different number of colors
            for($j = 1; $j < $request->colorsCount; $j++) {
                $colorFromWarehouse = 'row-'.$i.'-color'.$j;
                $wireColor = $wire->wireColors()->get()->values()->get($j-1);
                $wireColor->warehouse_product_spec_id = $request->$colorFromWarehouse ? $request->$colorFromWarehouse : '0';
                $wireColor->save();
            }
        }

        flash('A Amostra de Artigo com a referência: '. $sampleArticle->reference . ', e a descrição: '. $sampleArticle->description .' foi atualizada com sucesso!')->success();

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
            //dump("apagou colors");
            //Delete wires
            SampleArticlesWire::where('sample_article_id', $id)->delete();
            //dump("apagou wires");
            //Delete Sample Article
            $sampleArticle->delete();
            //dump("apagou artigo");
            flash('O Artigo com a referência: '. $sampleArticle->reference . ', e a descrição: '. $sampleArticle->description .' foi eliminado com sucesso!')->success();
        }

        return redirect()->action('SampleArticleController@index');
    }
}
