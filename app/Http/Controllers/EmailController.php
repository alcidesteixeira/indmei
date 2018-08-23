<?php

namespace App\Http\Controllers;

use App\Client;
use App\Mail\sendSimpleEmail;
use Illuminate\Http\Request;
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
        return view('emails.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();

        return view('emails.create', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        //dd($request->all());

        $res = Mail::to($request->client)->send(new sendSimpleEmail($request->subject, $request->body2));

        //dd($res);

        flash('Email enviado com sucesso!')->success();

        return redirect()->action('OrderController@index');
    }
}
