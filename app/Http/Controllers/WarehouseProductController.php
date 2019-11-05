<?php

namespace App\Http\Controllers;

use App\Order;
use App\SampleArticleColor;
use App\StockRequest;
use App\WarehouseProduct;
use App\WarehouseProductSpec;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Services\DataTable;

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

//        dd(isset($_COOKIE["update_warehouse"]));

//        $update = new WarehouseProduct();
//        $update->updateStocks();

        $stock = WarehouseProductSpec::all();

//        dd($stock); die;

        return view('warehouse.list', compact( 'stock'));
    }

//    public function getAllStocks()
//    {
//        $query = DB::table('warehouse_product_specs')
//            ->select(DB::raw('warehouse_product_specs.id, warehouse_products.reference, color,
//                gross_weight / 1000 as gross_weight, liquid_weight/1000 as liquid_weight, to_do_weight/1000 as to_do_weight,
//                threshold, cost, users.name, warehouse_product_specs.updated_at'))
//            ->leftJoin('warehouse_products', 'warehouse_products.id', '=', 'warehouse_product_specs.warehouse_product_id')
//            ->leftJoin('users', 'users.id', '=', 'warehouse_products.user_id')
//            ->get();
//
//        $datatable = Datatables::of($query)
//            ->addColumn('requested-stock', function ($product) {
//                $stock_request_history = DB::table('stock_request_history')
//                    ->orderBy('id', 'desc')
//                    ->get();
//
//                $stock_history = DB::table('warehouse_products_history')
//                    ->where('inout', 'IN')
//                    ->orderBy('id', 'desc')
//                    ->get();
//
//                $total_stock_requested = 0;
//                foreach($stock_request_history as $stock_request) {
//                    if($product->id == $stock_request->warehouse_product_spec_id) {
//                        $total_stock_requested += $stock_request->amount_requested;
//                    }
//                }
//                $total_stock_in = 0;
//                foreach($stock_history as $stock_in) {
//                    if ($product->id == $stock_in->warehouse_product_spec_id) {
//                        $weight = $stock_in->weight / 1000;
//                        $total_stock_in += $weight;
//                    }
//                }
//
//                return $stock_requested_differential = $total_stock_requested-$total_stock_in;
//            })
//            ->addColumn('action-edit', function ($product) {
//                return '<form method="get" action="stock/edit/'.$product->id.'" class="edit" enctype="multipart/form-data">
//                            <button type="submit" class="btn btn-warning">Editar</button>
//                        </form>';
//            })
//            ->addColumn('action-delete', function ($product) {
//                return '<button type="button" data-id="'.$product->id.'" data-role="'.$product->reference.'" class="delete apagarform btn btn-danger">Apagar</button>';
//            })
//            ->addColumn('action-stock', function ($product) {
//                return '<form method="get" action="/email/create/'.$product->id.'" class="email" enctype="multipart/form-data">
//                            <button type="submit" class="btn btn-success">Pedir stock</button>
//                        </form>';
//            })
//            ->setRowId('id')
//            ->rawColumns(['action-edit', 'action-delete', 'action-stock'])
//
//            ->removeColumn('id');
//
//        return $datatable
//            ->setRowClass(function ($product) {
//                $stock_request_history = DB::table('stock_request_history')
//                    ->orderBy('id', 'desc')
//                    ->get();
//
//                $stock_history = DB::table('warehouse_products_history')
//                    ->where('inout', 'IN')
//                    ->orderBy('id', 'desc')
//                    ->get();
//
//                $total_stock_requested = 0;
//                foreach($stock_request_history as $stock_request) {
//                    if($product->id == $stock_request->warehouse_product_spec_id) {
//                        $total_stock_requested += $stock_request->amount_requested;
//                    }
//                }
//                $total_stock_in = 0;
//                foreach($stock_history as $stock_in) {
//                    if ($product->id == $stock_in->warehouse_product_spec_id) {
//                        $weight = $stock_in->weight / 1000;
//                        $total_stock_in += $weight;
//                    }
//                }
//
//                $stock_requested_differential = $total_stock_requested-$total_stock_in;
//
//                return $product->liquid_weight < 0 && $stock_requested_differential < abs($product->liquid_weight) ? 'danger' : '';
//            })
//            ->make(true);
//    }

    public function returnHistoric($id)
    {
        $historic = DB::table('warehouse_products_history')
            ->leftJoin('users', 'warehouse_products_history.user_id', 'users.id')
            ->leftJoin('orders', 'warehouse_products_history.order_id', 'orders.id')
            ->select('order_id', 'orders.client_identifier_public', 'orders.description', DB::raw("SUM(weight) as sum_weight"), 'orders.client_identifier', 'orders.delivery_date', 'receipt')
            ->where('warehouse_product_spec_id', $id)
            ->where('orders.status_id', '5')
            ->where('inout', '<>', 'OUT_EXPIRED')
            ->orderBy('warehouse_products_history.created_at', 'desc')
            ->groupBy('order_id')
            ->get();

        //history of stock request and stock spent
        $stock_request_history = DB::table('stock_request_history')
            ->orderBy('id', 'desc')
            ->get();

        $stock_history = DB::table('warehouse_products_history')
            ->where('inout', 'IN')
            ->orderBy('id', 'desc')
            ->get();

        $email_content = '';
        $total_stock_requested = 0;
        foreach($stock_request_history as $stock_request) {
            if($id == $stock_request->warehouse_product_spec_id) {
                if($stock_request->email_sent !== 'adjust_entrada_stock_extra') {
                    $email_content .= 'Pedido: '.$stock_request->amount_requested.'Kg '.
                    'em '.substr($stock_request->created_at, 0, 10).' | ' ?: 0;
                }
                $total_stock_requested += $stock_request->amount_requested;
            }
        }
        $stock_in_latest = '';
        $total_stock_in = 0;
        foreach($stock_history as $stock_in) {
            if ($id == $stock_in->warehouse_product_spec_id) {
                $weight = $stock_in->weight / 1000;
                $stock_in_latest .= 'Entrada: ' . $weight . 'Kg ' .
                'em ' . substr($stock_in->created_at, 0, 10) . '| ' ?: 0;
                $total_stock_in += $weight;
            }
        }

        $all_data['historico'] = $historic;
        $all_data['pedido'] = $email_content;
        $all_data['entrada'] = $stock_in_latest;

        return $all_data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->authorizeRoles(['1', '5']);

        $products = new WarehouseProduct();
        $allProducts = $products->getProducts()->pluck('reference', 'id')->toArray();

        $colors = new WarehouseProductSpec();
        $allColors = $colors->getColors()->where('warehouse_product_id', key($allProducts))->pluck('color', 'id')->toArray();

        return view('warehouse.create', compact('stock', 'allProducts', 'allColors'));
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


        $products = new WarehouseProduct();
        $allProducts = $products->getProducts()->pluck('reference', 'id')->toArray();

        $colors = new WarehouseProductSpec();
        $allColors = $colors->getColors()->where('warehouse_product_id', key($allProducts))->pluck('color', 'id')->toArray();

        return view('warehouse.create', compact('stock', 'allProducts', 'allColors'));
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
        //$spec->cost = $request->cost;
        $spec->threshold = $request->threshold;
        $spec->save();

        //Update cost on history to display on stock
        DB::table('warehouse_products_history')->where('warehouse_product_spec_id', $id)
            ->orderBy('created_at', 'desc')
            ->update(['cost' => $request->cost]);

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
            flash('O Artigo com a referência: '. $ref . ', e a descrição: '. $spec->description .' não foi eliminada por está a ser utilizada por alguma amostra!')->error();
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

    public function simpleReceipt()
    {
        Auth::user()->authorizeRoles(['1', '5']);

        $products = new WarehouseProduct();
        $allProducts = $products->getProducts()->pluck('reference', 'id')->toArray();

        $colors = new WarehouseProductSpec();
        $allColors = $colors->getColors()->where('warehouse_product_id', key($allProducts))->pluck('color', 'id')->toArray();

        return view('warehouse.receipt_simple_include', compact('allProducts', 'allColors'));
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
                $warehouseProductSpec->liquid_weight = floatval($request->$qtd) * 1000;
                $warehouseProductSpec->gross_weight = floatval($request->$qtd) * 1000;
                if($request->$cost !== null){
                    $warehouseProductSpec->cost = $request->$cost;
                } else {
                    $warehouseProductSpec->cost = $warehouseProductSpec->cost;
                }
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

            //Tratar da saída de stock corretamente:
            if($request->$inout == 'OUT') {
                $arrayInout = ['OUT_GROSS', 'OUT_LIQUID'];
            }
            else if ($request->$inout == 'ADJUST') {
                $arrayInout = ['ADJUST'];
            } else {
                $arrayInout = ['IN'];
            }
            foreach ($arrayInout as $position) {
                DB::table('warehouse_products_history')->insert(
                    [
                        'warehouse_product_spec_id' => $warehouseProductSpec->id,
                        'user_id' => Auth::id(),
                        'inout' => $position,
                        'weight' => floatval($request->$qtd) * 1000,
                        'cost' => @$request->$cost ? @$request->$cost : $warehouseProductSpec->cost,
                        'description' => $request->$description,
                        'receipt' => $filename,
                        'created_at' => Carbon::now()->timezone('Europe/London'),
                        'updated_at' => Carbon::now()->timezone('Europe/London')
                    ]
                );
            }

            //Update Stock Requested
            if($request->$inout == 'IN') {
                $total_requested = DB::table('stock_request_history')
                    ->where('warehouse_product_spec_id', $warehouseProductSpec->id)
//                    ->where('email_sent', '<>', 'adjust_entrada_stock_extra')
                    ->get();
                $total_req = 0;
                foreach($total_requested as $total) {
                    $total_req += intval($total->amount_requested);
                }
                $warehouse_in_history = DB::table('warehouse_products_history')
                    ->where('warehouse_product_spec_id', $warehouseProductSpec->id)
                    ->where('inout', 'IN')
                    ->get();
                $total_in = 0;
                foreach($warehouse_in_history as $in_history) {
                    $total_in += intval($in_history->weight) / 1000;
                }

                $result_request = $total_req - $total_in;

//                dd($result_request, $total_req, $total_in);

                if($result_request < 0) {
                    DB::table('stock_request_history')
                        ->insert([
                            'warehouse_product_spec_id' => $warehouseProductSpec->id,
                            'amount_requested' => abs($result_request),
                            'email_sent' => 'adjust_entrada_stock_extra',
                            ]);
                }
            }


        }

        self::updateStockPedido($warehouseProductSpec);

        flash('Stock corretamente inserido!')->success();

        return redirect()->action('WarehouseProductController@index');
    }


    private function updateStockPedido($wh_obj) {

        $stock_request_history = DB::table('stock_request_history')
            ->orderBy('id', 'desc')
            ->get();

        $stock_history = DB::table('warehouse_products_history')
            ->where('inout', 'IN')
            ->orderBy('id', 'desc')
            ->get();

        $total_stock_requested = 0;
        foreach($stock_request_history as $stock_request) {
            if($wh_obj->id == $stock_request->warehouse_product_spec_id) {
                $total_stock_requested += $stock_request->amount_requested;
            }
        }
        $total_stock_in = 0;
        foreach($stock_history as $stock_in) {
            if ($wh_obj->id == $stock_in->warehouse_product_spec_id) {
                $weight = $stock_in->weight / 1000;
                $total_stock_in += $weight;
            }
        }

        $stock_requested_differential = $total_stock_requested-$total_stock_in;

        StockRequest::updateOrCreate(['warehouse_product_spec_id' => $wh_obj->id],
            ['amount_requested' => $stock_requested_differential]);

    }

}
