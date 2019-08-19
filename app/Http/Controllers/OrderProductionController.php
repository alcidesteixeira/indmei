<?php

namespace App\Http\Controllers;

use App\Mail\sendSimpleEmail;
use App\Order;
use App\OrderProduction;
use App\Role;
use App\SampleArticle;
use App\SampleArticleColor;
use App\SampleArticleGuiafio;
use App\SampleArticleStep;
use App\SampleArticlesWire;
use App\WarehouseProduct;
use App\WarehouseProductSpec;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->authorizeRoles(['1', '3', '4', '6', '7']);

        $orders = Order::where('status_id', 5)->get();

        $view = 'production';

        return view('orders.list', compact('orders', 'view'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($id)
    {
        Auth::user()->authorizeRoles(['1', '3', '4', '7']);

        $orders = OrderProduction::where('order_id', $id)->groupBy('user_id')->get();

        return view('orders.production.list', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id, $id_user = null)
    {
        Auth::user()->authorizeRoles(['1', '4', '6']);

        $order = Order::find($id);

        $guiafios = SampleArticleGuiafio::all();
        $steps = SampleArticleStep::all();
        $warehouseProducts = WarehouseProduct::all();
        $warehouseProductSpecs = WarehouseProductSpec::all();
        $lastDateWithData = OrderProduction::where('order_id', $id)->orderBy('created_at', 'desc')->first();
        if($lastDateWithData) {
            $lastDateWithDataHMS = $lastDateWithData->created_at;
            $lastDateWithData = $lastDateWithData->created_at->format('Y-m-d');
        }

//        MUDAR AQUI - FAZER O WHERE CREATED AT IGUAL A LASTDATEWITHDATA.
        $production = OrderProduction::where('order_id', $id)
            ->where('sample_article_id', $order->sample_article_id)
            ->where('created_at', @$lastDateWithDataHMS)
            ->groupBy('created_at')
            ->groupBy('machine_id')
            ->get();

        if(count($production) == 0) {
            $production = OrderProduction::where('order_id', $id)
                ->where('created_at', @$lastDateWithDataHMS)
                ->where('sample_article_id', '')
                ->groupBy('created_at')
                ->groupBy('machine_id')
                ->get();
        }

        //Criar array com valores para inserir em cada linha
        $productionTotal = OrderProduction::where('order_id', $id)->get();
        $arrayProdByMachine = [];
        foreach($production as $key =>$prod){
            $array = [];
            foreach ($productionTotal as $k => $v) {
                //Se data e maquina igual, entao trata-se da mesma linha
                if(substr($prod->created_at, 0, 13) == substr($v->created_at, 0, 13) && $prod->machine_id == $v->machine_id) {
                    $array[$v->tamanho . $v->cor] = $v->value;
                }
            }
            $arrayProdByMachine[$key+1] = $array;
        }
        $arrayProdByMachine = json_encode($arrayProdByMachine);

        $sample_id = Order::where('id', $id)->first()->sample_article_id;
        $sample = SampleArticlesWire::where('sample_article_id', $sample_id)
            ->get();
        $sample_colors = [];

        foreach($sample as $key => $sample_steps) {
            foreach (SampleArticleColor::where('sample_articles_wire_id', $sample_steps->id)->get() as $k => $color) {
                $sample_colors[$sample_steps->step_id][$k % 4 + 1] =
                    $color->warehouse_product_spec_id;
            }
        }

        $ww_colors = WarehouseProductSpec::all();
        $color_name_and_key_array = [];
        foreach($ww_colors as $color) {
            $color_name_and_key_array[$color->id] = $color->color;
        }
//        dd($sample_colors);

        return view(
            'orders.production.create', compact('order', 'guiafios', 'steps',
                'warehouseProducts', 'warehouseProductSpecs', 'production',
                'lastDateWithData', 'arrayProdByMachine', 'sample', 'sample_colors', 'color_name_and_key_array'
            ));
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
        if($request->rowsInserted) {
            $rows = explode(',', $request->rowsInserted);
            //Se máquinas inseridas forem repetidas, não grava e apresenta erro
            $arrayValidatedRepeated = [];
            for ($k = reset($rows); $k <= end($rows); $k++) {
                $machine = 'machineRow' . $k;
                if (in_array($request->$machine, $arrayValidatedRepeated)) {
                    flash('Não foi possivel armazenar os valores porque existem máquinas repetidas para o dia que está a inserir.')->error();
                    return redirect()->action('OrderProductionController@create', ['id' => $id]);
                }
                array_push($arrayValidatedRepeated, $request->$machine);
            }

            $order = Order::find($id);

            OrderProduction::where('created_at', '>', Carbon::today())
                ->where('created_at', '<', Carbon::tomorrow())
                ->where('sample_article_id', $order->sample_article_id)
                ->where('order_id', $id)
                ->delete();


            //reset = primeiro valor de array; end = ultimo valor do array
            for ($k = reset($rows); $k <= end($rows); $k++) {
                for ($i = 1; $i <= 4; $i++) {
                    for ($j = 1; $j <= 4; $j++) {
                        $cor = 'cor' . $k . $i . $j;
                        $machine = 'machineRow' . $k;
//                        if ($request->$cor !== '0') {
                            $orderProd = new OrderProduction;
                            $orderProd->user_id = Auth::id();
                            $orderProd->machine_id = $request->$machine;
                            $orderProd->order_id = $id;
                            $orderProd->tamanho = $i;
                            $orderProd->cor = $j;
                            $orderProd->value = $request->$cor;
                            $orderProd->created_at = Carbon::now('Europe/Lisbon');
                            $orderProd->updated_at = Carbon::now('Europe/Lisbon');
                            $orderProd->sample_article_id = $order->sample_article_id;
                            $orderProd->save();
//                        }
                    }
                }
            }

            //FALTA ATUALIZAR OS VALORES GASTOS EM ARMAZÉM
            //ATUALIZA HISTÓRICO DO PRODUTO DEPOIS DE INSERIR OS PARES PRODUZIDOS DO DIA
            $order = Order::where('id', $id)->first();
            $wireSpent = new Order();
            $wireSpent->addRowToStockHistory($order, $id);

        }

        flash('Valores atualizador para o dia: '. Carbon::now(). ' com sucesso!')->success();

        return redirect()->action('OrderProductionController@create', ['id' => $id]);
    }

    public function toSubtract($id) {

        //$totals = OrderProduction::where('order_id', $id)->get();

        $totals = DB::table('order_productions')
            ->select('tamanho', 'cor', 'value', DB::raw('sum(value) as total'))
            ->where('created_at', '<', Carbon::today())
            ->where('order_id', $id)
            ->groupBy('tamanho', 'cor' )
            ->get();

        $arrayTotals = [];
        foreach($totals as $total) {
            $arrayTotals['cor'.$total->tamanho.$total->cor] = abs($total->total);
        }
        return($arrayTotals);
    }

    public function orderEnded($id) {

        $order = Order::where('id', $id)->first();

        //Enviar email para criadores de encomendas indicando que uma amostra acabou de ser criada
        $users = Role::find(4)->users()->orderBy('name')->get();
        $subject = "Encomenda finalizada.";
        $body = "A encomenda abaixo descrita está finalizada: 
                <br>Nome do Cliente: ". $order->client->client ."
                <br>Identificador do Cliente: ". $order->client_identifier ."
                <br>Data de entrega da encomenda: ". $order->delivery_date ."
                <br>Descrição da encomenda: ". $order->description ."
                <br><br>
                Para aceder à encomenda, dirija-se à plataforma, ou clique 
                <a href='".url("/orders/list/")."' target='_blank'>aqui</a>.";
        foreach($users as $user) {
            Mail::to($user->email)->send(new sendSimpleEmail($subject, $body));
        }

        //Passar para estado terminado de produzir:
        $order = Order::find($id);
        $order->status_id = 7;
        $order->save();

        return ("email sent");
    }

    public function saveImageFinishedOrder(Request $request) {
        $imagedata = base64_decode($request->imgdata);
        $filename = md5(uniqid(rand(), true));
        //path where you want to upload image
        $file = public_path('/images/finishedOrders/finalorder'.$request->orderid.'.png');
        $imageurl  = $filename.'.png';
        file_put_contents($file,$imagedata);
        return $imageurl;
    }
}
