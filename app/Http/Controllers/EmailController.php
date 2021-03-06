<?php

namespace App\Http\Controllers;

use App\Client;
use App\Mail\sendSimpleEmail;
use App\StockRequest;
use App\Supplier;
use App\User;
use App\WarehouseProductSpec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        Auth::user()->authorizeRoles(['1', '3', '4', '5', '7']);

        return view('emails.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($stock_id = null)
    {
        //dd($stock_id);

        if(@$stock_id) {
            $prodSpec = WarehouseProductSpec::where('id', $stock_id)->first();
            $prodSpecArray = [];
            $prodSpecArray['descrição'] = $prodSpec->description;
            $prodSpecArray['referência'] = $prodSpec->product->reference;
            $prodSpecArray['cor'] = $prodSpec->color;
            $prodSpecArray['peso liquido'] = $prodSpec->liquid_weight;
            $prodSpecArray['peso bruto'] = $prodSpec->gross_weight;
            $prodSpecArray['custo pago por kg'] = $prodSpec->cost;
        }

        Auth::user()->authorizeRoles(['1', '3', '4', '5', '7']);

        $clients = Client::all();
        $suppliers = Supplier::all();
        $users = User::all();

        return view('emails.create', compact('clients', 'suppliers', 'users', 'prodSpecArray', 'stock_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        Auth::user()->authorizeRoles(['1', '3', '5', '7']);

        $receiver = $request->client !== '0' ? $request->client : $request->client ? $request->client : $request->new_address;

        if(isset($request->amountStockRequested)) {

            DB::table('stock_request_history')->insert([
                'warehouse_product_spec_id' => $request->id,
                'amount_requested' => $request->amountStockRequested,
                'email_sent' => 'enviado para: ' . $receiver . '; email: ' . $request->body2,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $stock_request_history = DB::table('stock_request_history')
                ->orderBy('id', 'desc')
                ->get();

            $stock_history = DB::table('warehouse_products_history')
                ->where('inout', 'IN')
                ->orderBy('id', 'desc')
                ->get();

            $total_stock_requested = 0;
            foreach($stock_request_history as $stock_request) {
                if($request->id == $stock_request->warehouse_product_spec_id) {
                    $total_stock_requested += $stock_request->amount_requested;
                }
            }
            $total_stock_in = 0;
            foreach($stock_history as $stock_in) {
                if ($request->id == $stock_in->warehouse_product_spec_id) {
                    $weight = $stock_in->weight / 1000;
                    $total_stock_in += $weight;
                }
            }


            $stock_requested_differential = $total_stock_requested-$total_stock_in;


            StockRequest::updateOrCreate(['warehouse_product_spec_id' => $request->id],
                ['amount_requested' => $stock_requested_differential, 'email_sent' => 'enviado para: ' . $receiver . '; email: ' . $request->body2]);
        }

        Mail::to($receiver)->send(new sendSimpleEmail($request->subject, $request->body2));

        flash('Email enviado com sucesso!')->success();

        if(isset($request->amountStockRequested)) {
            return redirect()->action('WarehouseProductController@index');
        }

        return redirect()->action('OrderController@index');
    }
}
