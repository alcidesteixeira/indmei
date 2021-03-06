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


        session_start();

        if(isset($_SESSION["update_warehouse"])) {
            $update = new WarehouseProduct();
            $update->updateStocks();
            session_unset();
        }

        $stock = WarehouseProductSpec::all();


        return view('warehouse.list', compact( 'stock'));
    }


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


        $update = new WarehouseProduct();
        $update->updateStocks($spec->id);

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


        $update = new WarehouseProduct();
        $update->updateStocks($id);

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

        $wiresArray = SampleArticleColor::where('warehouse_product_spec_id', $id)->groupBy('sample_articles_wire_id')->pluck('sample_articles_wire_id')->toArray();

        $samplesArray = DB::table('sample_articles_wires')
            ->select('reference', 'description')
            ->leftJoin('sample_articles', 'sample_articles_wires.sample_article_id', '=', 'sample_articles.id')
            ->whereIn('sample_articles_wires.id', array_values($wiresArray))
            ->groupBy('sample_articles_wires.sample_article_id')
            ->pluck('description', 'reference')
            ->toArray();


        if($samplesArray) {
            $message = 'O Artigo com a referência: '. $ref . ', e a descrição: '. $spec->description .' não foi eliminada porque está a ser utilizada pela(s) amostra(s): <br><ul>';
            foreach ($samplesArray as $key => $val) {
                $message .= '<li>' . $key . ' - ' . $val . '</li>';
            }
            $message .= '</ul>';
            flash( $message )->error();
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
                    $total_req += floatval($total->amount_requested);
                }
                $warehouse_in_history = DB::table('warehouse_products_history')
                    ->where('warehouse_product_spec_id', $warehouseProductSpec->id)
                    ->where('inout', 'IN')
                    ->get();
                $total_in = 0;
                foreach($warehouse_in_history as $in_history) {
                    $total_in += floatval($in_history->weight) / 1000;
                }

                $result_request = $total_req - $total_in;

//                dump($result_request, $total_req, $total_in);
//                dump('---');
//                dump(abs($result_request));
//                dump('---');

                if($result_request < 0) {
//                    dump('insert');
                    DB::table('stock_request_history')
                        ->insert([
                            'warehouse_product_spec_id' => $warehouseProductSpec->id,
                            'amount_requested' => abs($result_request),
                            'email_sent' => 'adjust_entrada_stock_extra',
                            'created_at' => now(),
                            'updated_at' => now(),
                            ]);
                }
            }


            $update = new WarehouseProduct();
            $update->updateStocks($warehouseProductSpec->id);

        }

        self::updateStockPedido($warehouseProductSpec->id);

        flash('Stock corretamente inserido!')->success();

        return redirect()->action('WarehouseProductController@index');
    }


    private function updateStockPedido($wh_id) {

        $stock_request_history = DB::table('stock_request_history')
            ->where('warehouse_product_spec_id', $wh_id)
            ->get();

        $stock_history = DB::table('warehouse_products_history')
            ->where('warehouse_product_spec_id', $wh_id)
            ->where('inout', 'IN')
            ->get();

        $total_stock_requested = 0;
        foreach($stock_request_history as $stock_request) {
            $total_stock_requested += floatval($stock_request->amount_requested);
        }
        $total_stock_in = 0;
        foreach($stock_history as $stock_in) {
            $total_stock_in += floatval($stock_in->weight) / 1000;
        }

        $stock_requested_differential = $total_stock_requested-$total_stock_in;

//        dump($stock_requested_differential, $total_stock_requested, $total_stock_in);


        StockRequest::updateOrCreate(['warehouse_product_spec_id' => $wh_id],
            ['amount_requested' => $stock_requested_differential]);

    }

}
