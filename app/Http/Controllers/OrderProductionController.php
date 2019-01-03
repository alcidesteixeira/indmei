<?php

namespace App\Http\Controllers;

use App\Mail\sendSimpleEmail;
use App\Order;
use App\OrderProduction;
use App\Role;
use App\SampleArticleGuiafio;
use App\SampleArticleStep;
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
        $production = OrderProduction::where('order_id', $id)->where('user_id', $id_user ? $id_user : Auth::id())->get();

        //create array of values to subtract stored
        /*$start = $order->created_at;
        $start=substr($start, 0, 10);
        $end = $order->delivery_date;
        $period = [];
        $current = strtotime($start);
        $today = strtotime(date("Y-m-d"));
        $last = strtotime($end);
        $i = $j = 0;
        if ($today >= $last) {
            while ($current <= $last) {
                $i++;
                $period[] = date('Y-m-d', $current);
                $current = strtotime('+1 day', $current);
            }
        }
        else {
            while ($current <= $today) {
                $j++;
                $period[] = date('Y-m-d', $current);
                $current = strtotime('+1 day', $current);
            }
        }
        //dd($period);
        $prod_days = [];
        // Iterate over the period
        foreach ($period as $date) {
            $prod_array = [];
            foreach($production as $prod) {
                //dump($date); dump(substr($prod->created_at, 0, 10));
                if($date == substr($prod->created_at, 0, 10)) {
                    $prod_array['val' . $prod->tamanho . $prod->cor] = $prod->value;
                }
            }
            $prod_days[$date] = $prod_array;
        }*/

        //dd($prod_days);
        //dd(@$order->sampleArticle->sampleArticleWires()->get()->values()->get(13)->warehouseProduct);

        /*
         * 1º levar todas as produções daquela encomenda e ordenar por data mais antiga primeiro
         * 2º adicionar uma linha em branco
         */
        //dd($production);
        $prod_days = [];


        return view(
            'orders.production.create', compact('order', 'guiafios', 'steps', 'warehouseProducts',
            'warehouseProductSpecs'
              ,  'prod_days'
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
        /*
         * Ao gravar, vai ter de procurar as linhas que são dessa encomenda, e atualizá-las ou criar novas se não existirem
         */

        $orderProd = OrderProduction::where('created_at', '>', Carbon::today())
            ->where('created_at', '<', Carbon::tomorrow())
            ->where('user_id', Auth::id())
            ->delete();

        //dd($request->all());
        for($i = 1; $i <= 4; $i++) {
            for($j = 1; $j <= 4; $j++) {
                $cor = 'cor'.$i.$j;
                if($request->$cor !== '0') {
                    $orderProd = new OrderProduction;
                    $orderProd->user_id = Auth::id();
                    $orderProd->order_id = $id;
                    $orderProd->tamanho = $i;
                    $orderProd->cor = $j;
                    $orderProd->value = $request->$cor;
                    $orderProd->created_at = Carbon::now('Europe/Lisbon');
                    $orderProd->updated_at = Carbon::now('Europe/Lisbon');
                    $orderProd->save();
                }
            }
        }

        //FALTA ATUALIZAR OS VALORES GASTOS EM ARMAZÉM
        //ATUALIZA HISTÓRICO DO PRODUTO DEPOIS DE INSERIR OS PARES PRODUZIDOS DO DIA
        $order = Order::where('id', $id)->first();
        $wireSpent = new Order();
        $wireSpent = $wireSpent->addRowToStockHistory ($order, $id);

//        dd($wireSpent);


        flash('Valores atualizador para o dia: '. Carbon::now(). ' com sucesso!')->success();

        return redirect()->action('OrderController@index');
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

        return ("email sended");
    }
}
