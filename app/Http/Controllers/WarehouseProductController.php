<?php

namespace App\Http\Controllers;

use App\WarehouseProduct;
use App\WarehouseProductSpec;
use Illuminate\Http\Request;
use Auth;

class WarehouseProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->authorizeRoles(['1', '5']);

        $stock = WarehouseProductSpec::all();

        return view('warehouse.list', compact('stock'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->authorizeRoles(['1', '5']);

        return view('warehouse.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Auth::user()->authorizeRoles(['1', '5']);

        //Store on WarehouseProduct Class
        $warehouseProduct= new WarehouseProduct();
        $warehouseProduct->user_id = Auth::id();
        $warehouseProduct->reference = $request->reference;
        $warehouseProduct->save();

        //Store on WarehouseProductSpec Class

        $spec = new WarehouseProductSpec();
        $spec->warehouse_product_id = $warehouseProduct->id;
        $spec->description = $request->description;
        $spec->color = $request->color;
        $spec->weight = $request->weight;
        $spec->threshold = $request->threshold;
        $spec->save();

        flash('Matéria-Prima com a referência: "'. $warehouseProduct->reference . '", e descrição: "'. $spec->description .'" foi criada com sucesso!')->success();

        return redirect()->action('WarehouseProductController@index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Auth::user()->authorizeRoles(['1', '5']);

        $stock = WarehouseProductSpec::find($id);

        return view('warehouse.create', compact('stock'));
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
        Auth::user()->authorizeRoles(['1', '5']);

        //Store on WarehouseProductSpec Class
        $spec = WarehouseProductSpec::find($id);
        $spec->warehouse_product_id = $spec->product->id;
        $spec->description = $request->description;
        $spec->color = $request->color;
        $spec->weight = $request->weight;
        $spec->threshold = $request->threshold;
        $spec->save();


        //Store on WarehouseProduct Class
        $warehouseProduct = WarehouseProduct::find($spec->product->id);
        $warehouseProduct->reference = $request->reference;
        $warehouseProduct->save();

        flash('A Matéria-Prima com a referência: '. $warehouseProduct->reference . ', e a descrição: '. $spec->description .' foi atualizada com sucesso!')->success();

        return redirect()->action('WarehouseProductController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Auth::user()->authorizeRoles(['1', '5']);

        $spec = WarehouseProductSpec::find($id);

        $ref = $spec->product->warehouse_product_id;

        $spec->delete();

        flash('O Artigo com a referência: '. $ref . ', e a descrição: '. $spec->description .' foi eliminado com sucesso!')->success();

        return redirect()->action('WarehouseProductController@index');
    }

    public function receipt()
    {
        Auth::user()->authorizeRoles(['1', '5']);

        $products = new WarehouseProduct();
        $allProducts = $products->getProducts()->pluck('reference')->toArray();

        $colors = new WarehouseProductSpec();
        $allColors = $colors->getColors()->pluck('color')->toArray();

        return view('warehouse.receipt', compact('allProducts', 'allColors'));
    }

    public function enterReceipt(Request $request)
    {
        Auth::user()->authorizeRoles(['1', '5']);

        dd($request->all());

        for($i = 1; $i <= $request->rowCount; $i++) {
            DB::table('users')->insert(
                ['email' => 'john@example.com', 'votes' => 0]
            );

            $warehouseHistory= new SampleArticle();
            $step = 'row-' . $i . '-step';
            $grams = 'row-' . $i . '-grams';
            $reference = 'row-' . $i . '-reference';

            $wire = $sampleArticle->sampleArticleWires()->get()->values()->get($i - 1);
            $wire->step_id = $request->$step;
            $wire->warehouse_product_id = $request->$reference;
            $wire->grams = $request->$grams;
            $wire->save();
        }
    }
}
