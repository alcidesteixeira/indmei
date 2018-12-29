<?php

namespace App\Http\Controllers;

use App\Client;
use App\Mail\sendSimpleEmail;
use App\Order;
use App\OrderStatus;
use App\Quotation;
use App\Role;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->authorizeRoles(['1', '3', '4', '6', '7']);

        $orders = Order::all();

        $view = 'quotations';

        return view('orders.list', compact('orders', 'view'));
    }

    /**
     * Criar novo orçamento pela primeira vez
     */
    public function store(Request $request)
    {
        $quotation = new Quotation;
        $quotation->order_id = $request->order_id;
        $quotation->order_sample_cost_1 = $request->order_cost1;
        $quotation->order_sample_cost_2 = $request->order_cost2;
        $quotation->order_sample_cost_3 = $request->order_cost3;
        $quotation->order_sample_cost_4 = $request->order_cost4;
        $quotation->tags = $request->tag;
        $quotation->boxes = $request->boxes;
        $quotation->defect = $request->defect;
        $quotation->manpower = $request->manpower;
        $quotation->other_costs = $request->other_costs;
        $quotation->value_sent = $request->total_sent;
        $quotation->save();

        $order = Order::where('id', $request->order_id)->first();

        $content =
            'Custo total: ' . intval($request->order_cost1 + $request->order_cost2 + $request->order_cost3 + $request->order_cost4) .
            ',<br> Etiquetas: ' . $request->tag .
            ',<br>Caixas: ' . $request->boxes .
            ',<br>Defeito: ' . $request->defect .
            ',<br>Mão de obra: ' . $request->manpower .
            ',<br>Outros Custos: ' . $request->other_costs .
            ',<br>Total: ' . $request->total_sent;

        //dd($content);

        //Enviar email para criadores de encomendas indicando que um orçamento acabou de ser criado
        $users = Role::find(4)->users()->orderBy('name')->get();
        $subject = "Novo orçamento efetuado.";
        $body = "Foi efetuada a orçamentação do produto descrito abaixo, e enviada ao cliente: 
                        <br>Identificador do Cliente: ". $order->client_identifier ."
                        <br>Cliente: ". $order->client->client ."
                        <br><br>
                        Para aceder à encomenda, dirija-se à plataforma, ou clique 
                        <a href='".url("/orders/list/")."' target='_blank'>aqui</a>.";
        foreach($users as $user) {
            Mail::to($user->email)->send(new sendSimpleEmail($subject, $body));
        }


            flash('Orçamento para a encomenda com Identificador: '. $order->client_identifier. ' do Cliente: ' . $order->client->client . ' foi criado com sucesso!<br>
                            Edite o email para enviar ao cliente que pretende.')->success();

        $clients = Client::all();
        $suppliers = Supplier::all();

        return view('emails.create', compact('clients', 'suppliers', 'content'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);

        $quotation = Quotation::where('order_id', $order->id)->first();

        $statuses = OrderStatus::all();


        $updateValueOfSamples = new Quotation();
        $updateValueOfSamples->updateValueOfSamples();


        return view('quotations.create', compact('order', 'statuses', 'quotation'));
    }

    /**
     * Atualizar orçamento já criado anteriormente
     */
    public function update(Request $request, $id)
    {

        //dd($request->all());
        $quotation = Quotation::where('id', $id)
            ->update([
                'order_sample_cost_1' => $request->order_cost1,
                'order_sample_cost_2' => $request->order_cost2,
                'order_sample_cost_3' => $request->order_cost3,
                'order_sample_cost_4' => $request->order_cost4,
                'tags' => $request->tag,
                'boxes' => $request->boxes,
                'defect' => $request->defect,
                'manpower' => $request->manpower,
                'other_costs' => $request->other_costs,
                'value_sent' => $request->total_sent,
                'updated_at' => Carbon::now(),

            ]);

        $order = Order::where('id', $request->order_id)->first();

        $content =
            'Custo total: ' . intval($request->order_cost1 * $request->color1 + $request->order_cost2 * $request->color2
                + $request->order_cost3 * $request->color3 + $request->order_cost4 * $request->color4) .
            ',<br> Etiquetas: ' . $request->tag .
            ',<br>Caixas: ' . $request->boxes .
            ',<br>Defeito: ' . $request->defect .
            ',<br>Mão de obra: ' . $request->manpower .
            ',<br>Outros Custos: ' . $request->other_costs .
            ',<br>Total: ' . $request->total_sent;


        //Enviar email para criadores de encomendas indicando que um orçamento acabou de ser criado
        $users = Role::find(4)->users()->orderBy('name')->get();
        $subject = "Novo orçamento efetuado.";
        $body = "Foi efetuada a orçamentação do produto descrito abaixo, e enviada ao cliente: 
                        <br>Identificador do Cliente: ". $order->client_identifier ."
                        <br>Cliente: ". $order->client->client ."
                        <br><br>
                        Para aceder à encomenda, dirija-se à plataforma, ou clique 
                        <a href='".url("/orders/list/")."' target='_blank'>aqui</a>.";
        foreach($users as $user) {
            Mail::to($user->email)->send(new sendSimpleEmail($subject, $body));
        }

        flash('Orçamento para a encomenda com Identificador: '. $order->client_identifier. ' do Cliente: ' . $order->client->client . ' foi atualizado com sucesso!<br>
                            Edite o email para enviar ao cliente que pretende.')->success();

        $clients = Client::all();
        $suppliers = Supplier::all();

        return view('emails.create', compact('clients', 'suppliers', 'content'));

    }

}
