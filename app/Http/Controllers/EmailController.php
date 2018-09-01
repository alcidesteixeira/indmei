<?php

namespace App\Http\Controllers;

use App\Client;
use App\Mail\sendSimpleEmail;
use App\Supplier;
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

        Auth::user()->authorizeRoles(['1', '3', '5', '7']);

        return view('emails.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        Auth::user()->authorizeRoles(['1', '3', '5', '7']);

        $clients = Client::all();
        $suppliers = Supplier::all();

        return view('emails.create', compact('clients', 'suppliers'));
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
