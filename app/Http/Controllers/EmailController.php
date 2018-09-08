<?php

namespace App\Http\Controllers;

use App\Client;
use App\Mail\sendSimpleEmail;
use App\Supplier;
use App\WarehouseProductSpec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return view('emails.create', compact('clients', 'suppliers', 'prodSpecArray'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {

        Auth::user()->authorizeRoles(['1', '3', '5', '7']);

        $receiver = $request->client !== '0' ? $request->client : $request->new_address;

        Mail::to($receiver)->send(new sendSimpleEmail($request->subject, $request->body2));

        flash('Email enviado com sucesso!')->success();

        return redirect()->action('OrderController@index');
    }
}
