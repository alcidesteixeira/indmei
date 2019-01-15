<?php

namespace App\Http\Controllers;

use App\Order;
use App\SampleArticleColor;
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


        //Calculate using historic;
        //Only updates the stock that has changed
        $update = new WarehouseProduct();
        $update = $update->updateStocks();



        //dd($update);

        $stock = WarehouseProductSpec::all();

        return view('warehouse.list', compact('stock'));
    }

    public function returnHistoric($id)
    {
        $historic = DB::table('warehouse_products_history')
            ->leftJoin('users', 'warehouse_products_history.user_id', 'users.id')
            ->select('user_id', 'name', 'inout', 'weight', 'cost', 'description', 'receipt', DB::raw("SUM(weight) as sum_weight"), 'warehouse_products_history.created_at')
//            ->sum('weight')
            ->where('warehouse_product_spec_id', $id)
            ->orderBy('warehouse_products_history.created_at', 'desc')
            ->groupBy('description')
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

        $hasReference = WarehouseProduct::where('reference', $request->reference)->first();

        if($hasReference) {
            $warehouseProduct = WarehouseProduct::find($hasReference->id);
        }
        else {
            //Store on WarehouseProduct Class
            $warehouseProduct = new WarehouseProduct();
            $warehouseProduct->user_id = Auth::id();
            $warehouseProduct->reference = $request->reference;
            $warehouseProduct->save();
        }

        //Store on WarehouseProductSpec Class
        $spec = new WarehouseProductSpec();
        $spec->warehouse_product_id = $warehouseProduct->id;
        $spec->description = $request->description;
        $spec->color = $request->color;
        $spec->liquid_weight = intval($request->liquid_weight) * 1000; //guardar em gramas
        $spec->gross_weight = @$request->gross_weight ? intval(@$request->gross_weight) * 1000 : intval($request->liquid_weight) * 1000; //ao criar, usa-se o bruto, ao atualizar, pode ser líquido
        $spec->cost = $request->cost;
        $spec->threshold = $request->threshold;
        $spec->save();

        //Inserir no history
        DB::table('warehouse_products_history')
            ->insert([
                'warehouse_product_spec_id' => $spec->id,
                'user_id' => Auth::id(),
                'inout' => 'IN',
                'weight' => intval($request->liquid_weight) * 1000,
                'cost' => $request->cost,
                'description' => $request->description,
                'receipt' => 'receipts/na.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

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
        $spec->liquid_weight = $request->liquid_weight;
        $spec->gross_weight = $request->gross_weight;
        $spec->cost = $request->cost;
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
        $productAmount = WarehouseProductSpec::where('warehouse_product_id', $spec->product->id)->get();

        $ref = $spec->product->reference;

        $isUsedSamples = SampleArticleColor::where('warehouse_product_spec_id', $id)->first();
        if($isUsedSamples) {
            flash('O Artigo com a referência: '. $ref . ', e a descrição: '. $spec->description .'Não foi eliminada por está a ser utilizada por alguma amostra!')->error();
            return redirect()->action('WarehouseProductController@index');
        }

        $spec->delete();

        //Caso seja a última referência daquela matéria prima, apagar tbm a matéria.
        if(count($productAmount) == 1){
            $product = WarehouseProduct::find($spec->product->id);
            $product->delete();
        }

        flash('O Artigo com a referência: '. $ref . ', e a descrição: '. $spec->description .' foi eliminado com sucesso!')->success();

        return redirect()->action('WarehouseProductController@index');
    }

    public function receipt()
    {
        Auth::user()->authorizeRoles(['1', '5']);

        $products = new WarehouseProduct();
        $allProducts = $products->getProducts()->pluck('reference', 'id')->toArray();

        $colors = new WarehouseProductSpec();
        $allColors = $colors->getColors()->where('warehouse_product_id', key($allProducts))->pluck('color', 'id')->toArray();

        return view('warehouse.receipt', compact('allProducts', 'allColors'));
    }

    /**
     * Returns all colors based on the selected product
     */
    public function allColors ($id)
    {

        $colors = new WarehouseProductSpec();
        $allColors = $colors->getColors()->where('warehouse_product_id', $id)->pluck('color', 'id')->toArray();


        return ($allColors);
    }

    public function enterReceipt(Request $request)
    {

        Auth::user()->authorizeRoles(['1', '5']);

        if($request->file('receipt') && !str_contains($request->file('receipt')->getClientOriginalName(), ['pdf', 'jpg', 'png', 'gif', 'JPG', 'PNG', 'GIF'])) {
            flash('Por favor, insira uma extensão válida. (.pdf, .jpeg, .png, .gif)')->error();

            return redirect()->action('WarehouseProductController@receipt');
        }

        for($i = 1; $i <= $request->rowCount; $i++) {

            //ir buscar warehouseproduct onde reference seja igual a inserida e obter id
            //verificar nos warehouse product specs existe um id com warehouseproductid igual ao em cima e color igual à inserida na tabela
            $inout = 'inout-'.$i;
            $reference = 'reference-'.$i;
            $color = 'color-'.$i;
            $cost = 'cost-'.$i;
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
                $warehouseProductSpec->liquid_weight = intval($request->$qtd) * 1000;
                $warehouseProductSpec->gross_weight = intval($request->$qtd) * 1000;
                $warehouseProductSpec->cost = $request->$cost;
                $warehouseProductSpec->threshold = $request->$threshold ? $request->$threshold : 1000;
                $warehouseProductSpec->save();
            }

            //Em qualquer caso, adiciona sempre no histórico: caso nao exista fio; caso não exista cor; caso exista fio e cor
            $file = $request->file('receipt');
            if($file) {
                $extension = str_contains($file->getClientOriginalName(), 'pdf') ? '.pdf' : '.jpg';
                $filename = 'receipts/' . explode('.', $file->getClientOriginalName())[0] . '-' . Carbon::now('Europe/London')->format('YmdHis') . $extension;
                Storage::disk('public')->put($filename, File::get($file));
            }
            else {
                $filename = 'receipts/white.png';
            }

            DB::table('warehouse_products_history')->insert(
                [
                    'warehouse_product_spec_id' => $warehouseProductSpec->id,
                    'user_id' => Auth::id(),
                    'inout' => $request->$inout,
                    'weight' => intval($request->$qtd) * 1000,
                    'cost' => @$request->$cost ? @$request->$cost : $warehouseProductSpec->cost,
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

    public function requestStock()
    {
        return view('warehouse.request_stock');
    }

    public function storeRequestedStock()
    {
        return view('warehouse.list_stock_requested');
    }

    public function StockRequestedHistory()
    {
        return view('warehouse.list_stock_requested');
    }


}
