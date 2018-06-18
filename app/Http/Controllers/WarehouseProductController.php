<?php

namespace App\Http\Controllers;

use App\WarehouseProduct;
use App\WarehouseProductSpec;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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

    public function returnHistoric($id)
    {
        $historic = DB::table('warehouse_products_history')
            ->select('user_id', 'inout', 'weight', 'description', 'receipt', 'updated_at')
            ->where('warehouse_product_spec_id', $id)
            ->get();

        return $historic;
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

        for($i = 1; $i <= $request->rowCount; $i++) {

            //ir buscar warehouseproduct onde reference seja igual a inserida e obter id
            //verificar nos warehouse product specs existe um id com warehouseproductid igual ao em cima e color igual à inserida na tabela
            $inout = 'inout-'.$i;
            $reference = 'reference-'.$i;
            $color = 'color-'.$i;
            $qtd = 'qtd-'.$i;
            $description = 'description-'.$i;
            $threshold = 'threshold-'.$i;

            $warehouseProduct = WarehouseProduct::where('reference', $request->$reference)->first();

            //se retornar id, fazer o insert no histórico; senão tem de inserir linha na warehouse product, ou na warehouse product e na warehouse product spec
            //se não existir fio:
            if(!($warehouseProduct)) {
                $warehouseProduct = new WarehouseProduct();
                $warehouseProduct->user_id = Auth::id();
                $warehouseProduct->reference = $request->$reference;
                $warehouseProduct->save();
            }
            //se não existir cor nem fio
            $warehouseProductSpec = WarehouseProductSpec::where('warehouse_product_id', $warehouseProduct->id)->where('color',$request->$color)->first();

            if(!($warehouseProduct) || !($warehouseProductSpec)) {
                if($warehouseProduct) {
                    $warehouseProduct= WarehouseProduct::find($warehouseProduct->id);
                }
                $warehouseProductSpec = new WarehouseProductSpec();
                $warehouseProductSpec->warehouse_product_id = $warehouseProduct->id;
                $warehouseProductSpec->description = $request->$description;
                $warehouseProductSpec->color = $request->$color;
                $warehouseProductSpec->weight = $request->$qtd;
                $warehouseProductSpec->threshold = $request->$threshold ? $request->$threshold : 1000;
                $warehouseProductSpec->save();
            }
            //Em qualquer caso, adiciona sempre no histórico: caso nao exista fio; caso não exista cor; caso exista fio e cor
            $file = $request->file('receipt');
            if($file) {
                $filename = 'receipts/' . explode('.', $file->getClientOriginalName())[0] . '-' . Carbon::now('Europe/London')->format('YmdHis') . '.jpg';
                Storage::disk('public')->put($filename, File::get($file));
            }

            DB::table('warehouse_products_history')->insert(
                [
                    'warehouse_product_spec_id' => $warehouseProductSpec->id,
                    'user_id' => Auth::id(),
                    'inout' => $request->$inout,
                    'weight' => $request->$qtd,
                    'description' => $request->$description,
                    'receipt' => $filename,
                    'created_at' => Carbon::now()->timezone('Europe/London'),
                    'updated_at' => Carbon::now()->timezone('Europe/London')
                ]
            );

        }

        flash('Stock corretamente inserido!')->success();

        return redirect()->action('WarehouseProductController@index');
    }
}
