<?php

namespace App\Http\Controllers;

use App\Mail\sendSimpleEmail;
use App\Order;
use App\Role;
use App\SampleArticle;
use App\SampleArticleColor;
use App\SampleArticleGuiafio;
use App\OrderStatus;
use App\SampleArticleStep;
use App\SampleArticlesWire;
use App\WarehouseProduct;
use App\WarehouseProductSpec;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
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

        $warehouseProducts = WarehouseProduct::orderBy('reference', 'asc')->get();

        $warehouseFirstWireSpecs = WarehouseProduct::orderBy('reference', 'asc')->first();
        if($warehouseFirstWireSpecs) {
            $warehouseFirstWireSpecs = $warehouseFirstWireSpecs->warehouseProductSpecs()->get();
        }

        $guiafios = SampleArticleGuiafio::all();

        $sampleIdsAndDesc = SampleArticle::all('id', 'reference', 'description');
        //dd($sampleIdsAndDesc);

        return view('samples.create', compact('steps', 'warehouseProducts', 'warehouseFirstWireSpecs', 'guiafios', 'sampleIdsAndDesc'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
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
        else {
            $sampleArticle->image_url = $request->img_path_duplicated;
        }
        //End Store Image
        $sampleArticle->tamanho1 = $request->tamanho1 ? $request->tamanho1 : '0';
        $sampleArticle->pe1 = $request->pe1 ? $request->pe1 : '0';
        $sampleArticle->perna1 = $request->perna1 ? $request->perna1 : '0';
        $sampleArticle->punho1 = $request->punho1 ? $request->punho1 : '0';
        $sampleArticle->malha1 = $request->malha1 ? $request->malha1 : '0';
        $sampleArticle->maq1 = $request->maq1 ? $request->maq1 : '0';
        $sampleArticle->forma1 = $request->forma1 ? $request->forma1 : '0';
        $sampleArticle->tamanho2 = $request->tamanho2 ? $request->tamanho2 : '0';
        $sampleArticle->pe2 = $request->pe2 ? $request->pe2 : '0';
        $sampleArticle->perna2 = $request->perna2 ? $request->perna2 : '0';
        $sampleArticle->punho2 = $request->punho2 ? $request->punho2 : '0';
        $sampleArticle->malha2 = $request->malha2 ? $request->malha2 : '0';
        $sampleArticle->maq2 = $request->maq2 ? $request->maq2 : '0';
        $sampleArticle->forma2 = $request->forma2 ? $request->forma2 : '0';
        $sampleArticle->tamanho3 = $request->tamanho3 ? $request->tamanho3 : '0';
        $sampleArticle->pe3 = $request->pe3 ? $request->pe3 : '0';
        $sampleArticle->perna3 = $request->perna3 ? $request->perna3 : '0';
        $sampleArticle->punho3 = $request->punho3 ? $request->punho3 : '0';
        $sampleArticle->malha3 = $request->malha3 ? $request->malha3 : '0';
        $sampleArticle->maq3 = $request->maq3 ? $request->maq3 : '0';
        $sampleArticle->forma3 = $request->forma3 ? $request->forma3 : '0';
        $sampleArticle->tamanho4 = $request->tamanho4 ? $request->tamanho4 : '0';
        $sampleArticle->pe4 = $request->pe4 ? $request->pe4 : '0';
        $sampleArticle->perna4 = $request->perna4 ? $request->perna4 : '0';
        $sampleArticle->punho4 = $request->punho4 ? $request->punho4 : '0';
        $sampleArticle->malha4 = $request->malha4 ? $request->malha4 : '0';
        $sampleArticle->maq4 = $request->maq4 ? $request->maq4 : '0';
        $sampleArticle->forma4 = $request->forma4 ? $request->forma4 : '0';
        $sampleArticle->cost1 = round($sampleCost['cor1'], 2);
        $sampleArticle->cost2 = round($sampleCost['cor2'], 2);
        $sampleArticle->cost3 = round($sampleCost['cor3'], 2);
        $sampleArticle->cost4 = round($sampleCost['cor4'], 2);
        $sampleArticle->cor1 = $request->cor1;
        $sampleArticle->cor2 = $request->cor2;
        $sampleArticle->cor3 = $request->cor3;
        $sampleArticle->cor4 = $request->cor4;
        $sampleArticle->save();

        //Store on SampleArticleWire Class
        //Run for each row of the table
        for($i = 1; $i < $request->rowCount; $i++) {
            $step = 'row-'.$i.'-step';
            $grams = 'row-'.$i.'-grams';
            $reference = 'row-'.$i.'-reference';
            $guiafios = 'row-'.$i.'-guiafios';
            $wire = new sampleArticlesWire();
            $wire->sample_article_id = $sampleArticle->id;
            $wire->step_id = $request->$step ? $request->$step : '0';
            $wire->warehouse_product_id = $request->$reference;
            $wire->guiafios_id = $request->$guiafios ?: 9;
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


        //Enviar email para criadores de encomendas indicando que uma amostra acabou de ser criada
        /*$users = Role::find(4)->users()->orderBy('name')->get();
        $subject = "Nova amostra criada.";
        $body = "Uma amostra de produto foi terminada e pode ser utilizada nas suas encomendas:
                        <br>Referência da Amostra INDMEI: ". $request->reference ."
                        <br>Descrição: ". $request->description ."
                        <br>Imagem: <br><img src='". url('storage/'.$sampleArticle->image_url) ."' style='width:300px'>
                        <br><br>
                        Para aceder à encomenda, dirija-se à plataforma, ou clique
                        <a href='".url("/orders/list/")."' target='_blank'>aqui</a>.";
        foreach($users as $user) {
            Mail::to($user->email)->send(new sendSimpleEmail($subject, $body));
        } */

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
        Auth::user()->authorizeRoles(['1', '3', '7']);

        $sampleArticle = SampleArticle::find($id);

        $sample_wires = $sampleArticle->sampleArticleWires()->get();

        $sample_wire_ids_array =
        $sample_guiafios_array =
        $sample_steps_array =
        $sample_grams_array =
        $sample_wp_array =
        $sample_wp_specs_array = [];

        foreach($sample_wires as $key => $wire){
            $sample_wire_ids_array[] = $wire->id;
            $sample_guiafios_array[] = $wire->guiafios_id;
            $sample_steps_array[] = $wire->step_id;
            $sample_grams_array[] = $wire->grams;
            $sample_wp_array[] = $wire->warehouse_product_id;
        }

        $warehouseProductSpecs = WarehouseProductSpec::all();
        foreach($warehouseProductSpecs as $spec) {
            $warehouseProductSpecsArray[$spec->warehouse_product_id][$spec->id] = $spec->color;
        }

        $warehouseProductSpecsColors = DB::table('sample_article_colors')
            ->get()
            ->toArray();

        foreach($sample_wire_ids_array as $wire_key => $wire) {
            foreach($warehouseProductSpecsColors as $color_key => $color) {
                if($wire == $color->sample_articles_wire_id) {
                    $sample_wp_specs_array[$wire_key][] = $color->warehouse_product_spec_id;
                }
            }
        }
        $steps = SampleArticleStep::all();

        $warehouseProducts = WarehouseProduct::orderBy('reference', 'asc')->get();

        $guiafios = SampleArticleGuiafio::pluck('description', 'id')->toArray();

//        dd($sampleArticle->sampleArticleWires()->get()->values()->get(16)->guiafios_id);
//        dd($sampleArticle->sampleArticleWires()->get()->values()->get(16)->wireColors()->get());
//        dd($sampleArticle->sampleArticleWires()->get()->values()->get(16)->warehouseProduct->warehouseProductSpecs()->get());

        return view('samples.create',
            compact('sampleArticle', 'steps', 'sample_steps_array',
                'warehouseProducts', 'guiafios', 'sample_guiafios_array', 'sample_grams_array',
                'sample_wp_array', 'warehouseProductSpecsArray', 'sample_wp_specs_array', 'id'));
    }

    public function updateWireSpecs($id)
    {

        $warehousetWireSpecs = WarehouseProduct::find($id)->warehouseProductSpecs()->get();

        foreach($warehousetWireSpecs as $spec) {
            $warehousetWireSpecsArray [$spec->id] = $spec->color;
        }
        asort($warehousetWireSpecsArray);
        $i = 0;
        foreach($warehousetWireSpecsArray as $key => $value){
            $array[$i] = ['id' => $key, 'name' => $value];
            $i++;
        }

        return ($array);
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
        //dd($request->all());
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
        $sampleArticle->tamanho1 = $request->tamanho1 ? $request->tamanho1 : '0';
        $sampleArticle->pe1 = $request->pe1 ? $request->pe1 : '0';
        $sampleArticle->perna1 = $request->perna1 ? $request->perna1 : '0';
        $sampleArticle->punho1 = $request->punho1 ? $request->punho1 : '0';
        $sampleArticle->malha1 = $request->malha1 ? $request->malha1 : '0';
        $sampleArticle->maq1 = $request->maq1 ? $request->maq1 : '0';
        $sampleArticle->forma1 = $request->forma1 ? $request->forma1 : '0';
        $sampleArticle->tamanho2 = $request->tamanho2 ? $request->tamanho2 : '0';
        $sampleArticle->pe2 = $request->pe2 ? $request->pe2 : '0';
        $sampleArticle->perna2 = $request->perna2 ? $request->perna2 : '0';
        $sampleArticle->punho2 = $request->punho2 ? $request->punho2 : '0';
        $sampleArticle->malha2 = $request->malha2 ? $request->malha2 : '0';
        $sampleArticle->maq2 = $request->maq2 ? $request->maq2 : '0';
        $sampleArticle->forma2 = $request->forma2 ? $request->forma2 : '0';
        $sampleArticle->tamanho3 = $request->tamanho3 ? $request->tamanho3 : '0';
        $sampleArticle->pe3 = $request->pe3 ? $request->pe3 : '0';
        $sampleArticle->perna3 = $request->perna3 ? $request->perna3 : '0';
        $sampleArticle->punho3 = $request->punho3 ? $request->punho3 : '0';
        $sampleArticle->malha3 = $request->malha3 ? $request->malha3 : '0';
        $sampleArticle->maq3 = $request->maq3 ? $request->maq3 : '0';
        $sampleArticle->forma3 = $request->forma3 ? $request->forma3 : '0';
        $sampleArticle->tamanho4 = $request->tamanho4 ? $request->tamanho4 : '0';
        $sampleArticle->pe4 = $request->pe4 ? $request->pe4 : '0';
        $sampleArticle->perna4 = $request->perna4 ? $request->perna4 : '0';
        $sampleArticle->punho4 = $request->punho4 ? $request->punho4 : '0';
        $sampleArticle->malha4 = $request->malha4 ? $request->malha4 : '0';
        $sampleArticle->maq4 = $request->maq4 ? $request->maq4 : '0';
        $sampleArticle->forma4 = $request->forma4 ? $request->forma4 : '0';
        $sampleArticle->cost1 = round($sampleCost['cor1'], 2);
        $sampleArticle->cost2 = round($sampleCost['cor2'], 2);
        $sampleArticle->cost3 = round($sampleCost['cor3'], 2);
        $sampleArticle->cost4 = round($sampleCost['cor4'], 2);
        $sampleArticle->cor1 = $request->cor1;
        $sampleArticle->cor2 = $request->cor2;
        $sampleArticle->cor3 = $request->cor3;
        $sampleArticle->cor4 = $request->cor4;
        $sampleArticle->save();

        //dd($request->all());
        //dd($sampleArticle->sampleArticleWires()->get()->values()->get(0)->wireColors()->get()->values()->get(0)->warehouse_product_spec_id);

        //Store on SampleArticleWire Class
        //Run for each row of the table
        for($i = 1; $i < $request->rowCount; $i++) {
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


        //Atualizar valores de Armazém, no momento em que uma amostra é editada
        $sampleID = $sampleArticle->id;
        //dd($sampleID);
        //Update all OUT for gross calculations
        $orders = Order::where('sample_article_id', $sampleID)->get();
        //Ao fazer isto, estaria a atualizar sempre que entra na lista de encomendas, e isso iria afetar os resultados
        foreach($orders as $order) {
            if($order->sample_article_id) {
                $grossCalcsResults = new Order();
                $grossCalcsResults = $grossCalcsResults->addRowToStockHistory($order, $order->id);
            }
        }

        //Enviar email para criadores de encomendas indicando que uma amostra acabou de ser criada
        /*$users = Role::find(4)->users()->orderBy('name')->get();
        $subject = "Nova amostra criada.";
        $body = "Uma amostra de produto foi terminada e pode ser utilizada nas suas encomendas:
                        <br>Referência da Amostra INDMEI: ". $request->reference ."
                        <br>Descrição: ". $request->description ."
                        <br>Imagem: <br><img src='". url('storage/'.$sampleArticle->image_url) ."' style='width:300px'>
                        <br><br>
                        Para aceder à amostra, dirija-se à plataforma, ou clique
                        <a href='".url("/orders/list/")."' target='_blank'>aqui</a>.";
        foreach($users as $user) {
            Mail::to($user->email)->send(new sendSimpleEmail($subject, $body));
        } */

        session_start();
        $_SESSION["update_warehouse"] = true;

        flash('A Amostra de Artigo com a referência: '. $sampleArticle->reference . ', e a descrição: '. $sampleArticle->description .' foi atualizada com sucesso!')->success();

        return redirect()->action('SampleArticleController@index');
    }

    /**
     * Duplicate Sample Article
     */
    public function duplicate($id){

        Auth::user()->authorizeRoles(['1', '3', '7']);

        $sampleArticle = SampleArticle::find($id);

        $steps = SampleArticleStep::all();

        $warehouseProducts = WarehouseProduct::all();

        $guiafios = SampleArticleGuiafio::all();

        $isDuplicate = 1;
        $sampleIdsAndDesc = SampleArticle::all('id', 'reference', 'description');


        $sample_wires = $sampleArticle->sampleArticleWires()->get();

        $sample_wire_ids_array =
        $sample_guiafios_array =
        $sample_steps_array =
        $sample_grams_array =
        $sample_wp_array =
        $sample_wp_specs_array = [];

        foreach($sample_wires as $key => $wire){
            $sample_wire_ids_array[] = $wire->id;
            $sample_guiafios_array[] = $wire->guiafios_id;
            $sample_steps_array[] = $wire->step_id;
            $sample_grams_array[] = $wire->grams;
            $sample_wp_array[] = $wire->warehouse_product_id;
        }

        $warehouseProductSpecs = WarehouseProductSpec::all();
        foreach($warehouseProductSpecs as $spec) {
            $warehouseProductSpecsArray[$spec->warehouse_product_id][$spec->id] = $spec->color;
        }

        $warehouseProductSpecsColors = DB::table('sample_article_colors')
            ->get()
            ->toArray();

        foreach($sample_wire_ids_array as $wire_key => $wire) {
            foreach($warehouseProductSpecsColors as $color_key => $color) {
                if($wire == $color->sample_articles_wire_id) {
                    $sample_wp_specs_array[$wire_key][] = $color->warehouse_product_spec_id;
                }
            }
        }
        $steps = SampleArticleStep::all();

        $warehouseProducts = WarehouseProduct::orderBy('reference', 'asc')->get();

        $guiafios = SampleArticleGuiafio::pluck('description', 'id')->toArray();

        return view('samples.create',
            compact('sampleArticle', 'steps', 'sample_steps_array',
                'warehouseProducts', 'guiafios', 'sample_guiafios_array', 'sample_grams_array',
                'sample_wp_array', 'warehouseProductSpecsArray', 'sample_wp_specs_array', 'id',
                'isDuplicate', 'sampleIdsAndDesc'));

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

        $isUsedOrders = Order::where('sample_article_id', $id)->first();

        //dd($isNotLastStatus);

        if($isUsedOrders) {
            flash('Atenção! A Amostra de Artigo com a referência '. $sampleArticle->reference . ' não pode ser eliminado pois encontra-se associado a Encomendas!
                <br> Remova esta amostra das encomendas para poder ser eliminada!')->error();

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
