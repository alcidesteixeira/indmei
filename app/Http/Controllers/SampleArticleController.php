<?php

namespace App\Http\Controllers;

use App\SampleArticle;
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

        $warehouseProducts = WarehouseProduct::groupBy('reference')->get();

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

        dd($request->all());

        //Store on SampleArticle Class
        $sampleArticle= new SampleArticle();
        $sampleArticle->user_id = Auth::id();
        $sampleArticle->reference = $request->get('reference');
        $sampleArticle->description = $request->get('description');
        $sampleArticle->image_url = $request->get('image_url');
        $sampleArticle->sample_article_status_id = $request->get('sample_article_status_id');
        $sampleArticle->pe = $request->get('pe');
        $sampleArticle->perna = $request->get('perna');
        $sampleArticle->punho = $request->get('punho');
        $sampleArticle->malha = $request->get('malha');
        $sampleArticle->maq = $request->get('maq');
        $sampleArticle->forma = $request->get('forma');
        $sampleArticle->save();

        //Store on SampleArticleWire Class
        for($i = 1; $i <= $request->rowCount; $i++) {
            $step = 'row-'.$i.'-step';
            $step = 'row-'.$i.'-step';
            $wire = new sampleArticlesWire();
            $wire->sample_article_id = $sampleArticle->id;
            $wire->step_id = $sampleArticle->$step;

        }



        //Store on SampleArticleColor Class





        flash('Amostra de Artigo com a referência '. $sampleArticle->reference . ' foi criada com sucesso!')->success();

        return redirect()->action('SampleArticleController@index');

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
