<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderStatus;
use App\Quotation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuotationController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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

        flash('Orçamento para a encomenda com Identificador: '. $order->client_identifier. ' do Cliente: ' . $order->client->client . ' foi criado com sucesso!')->success();

        return redirect()->action('EmailController@create');
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

//        dd($quotation);

        return view('quotations.create', compact('order', 'statuses', 'quotation'));
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

        flash('Orçamento para a encomenda com Identificador: '. $order->client_identifier. ' do Cliente: ' . $order->client->client . ' foi atualizado com sucesso!')->success();

        return redirect()->action('EmailController@create');

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
