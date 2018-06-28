<?php

namespace App\Http\Controllers;

use App\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->authorizeRoles(['1', '4']);

        $clients = Client::all();

        return view('clients.list', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->authorizeRoles(['1', '4']);

        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Auth::user()->authorizeRoles(['1', '4']);

        $client= new Client();
        $client->client = $request->client;
        $client->nif = $request->nif;
        $client->description = $request->description;
        $client->save();

        flash('Cliente '. $client->supplier . ' foi criado com sucesso!')->success();

        return redirect()->action('ClientController@index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Auth::user()->authorizeRoles(['1', '4']);

        $client = Client::find($id);
        return view('clients.create', compact('client','id'));
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
        Auth::user()->authorizeRoles(['1', '4']);

        $client= Client::find($id);
        $client->client = $request->client;
        $client->nif = $request->nif;
        $client->description = $request->description;
        $client->updated_at = Carbon::now('Europe/Lisbon');
        $client->save();

        flash('Cliente '. $client->supplier . ' foi atualizado com sucesso!')->success();

        return redirect()->action('ClientController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Auth::user()->authorizeRoles(['1', '4']);

        $client = Client::find($id);

        $ordersWithThisClient = Client::find($id)->orders()->first();
        //dd($usersWithThisRole);
        if($ordersWithThisClient) {

            flash('Atenção! O Cliente '. $client->supplier . ' não pode ser eliminado pois está associado a alguma encomenda! <br> Altere o fornecedor da encomenda antes de poder apagar o fornecedor!')->error();

        }
        else {

            $client->delete();

            flash('Cliente '. $client->supplier . ' foi eliminado com sucesso!')->success();
        }

        return redirect()->action('ClientController@index');
    }
}
