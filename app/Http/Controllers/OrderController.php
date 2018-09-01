<?php

namespace App\Http\Controllers;

use App\Client;
use App\Order;
use App\OrderFile;
use App\SampleArticle;
use App\OrderStatus;
use App\SampleArticlesWire;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->authorizeRoles(['1', '4', '6', '7']);

        $orders = Order::all();

        return view('orders.list', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->authorizeRoles(['1', '4']);

        $sampleArticles = SampleArticle::all();

        $clients = Client::all();

        $statuses = OrderStatus::all();

        return view('orders.create', compact('sampleArticles', 'clients', 'statuses'));
    }

    public function getSampleArticleId ($id) {

        $sampleId = SampleArticle::find($id);

        return $sampleId;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //dd($request->all());
        Auth::user()->authorizeRoles(['1', '4']);

        $order= new Order();
        $order->user_id =  Auth::id();
        $order->status_id = $request->status_id;
        $order->sample_article_id =  $request->sample_article_id;
        $order->client_id =  $request->client_id;
        $order->client_identifier = $request->client_identifier;
        $order->delivery_date = $request->delivery_date;
        $order->description = $request->description;
        $order->cor1 = $request->cor1;
        $order->cor2 = $request->cor2;
        $order->cor3 = $request->cor3;
        $order->cor4 = $request->cor4;
        $order->tamanho11 = $request->tamanho11;
        $order->tamanho12 = $request->tamanho12;
        $order->tamanho13 = $request->tamanho13;
        $order->tamanho14 = $request->tamanho14;
        $order->tamanho21 = $request->tamanho21;
        $order->tamanho22 = $request->tamanho22;
        $order->tamanho23 = $request->tamanho23;
        $order->tamanho24 = $request->tamanho24;
        $order->tamanho31 = $request->tamanho31;
        $order->tamanho32 = $request->tamanho32;
        $order->tamanho33 = $request->tamanho33;
        $order->tamanho34 = $request->tamanho34;
        $order->tamanho41 = $request->tamanho41;
        $order->tamanho42 = $request->tamanho42;
        $order->tamanho43 = $request->tamanho43;
        $order->tamanho44 = $request->tamanho44;
        $order->save();

        //Store Image
        foreach ($request->order_files_id as $order_files_id) {
            $orderFile= new OrderFile();
            $file = $order_files_id;
            if ($file) {
                $filename = 'orders/' . explode('.', $file->getClientOriginalName())[0] . '-' . Carbon::now('Europe/London')->format('YmdHis') . '.jpg';
                Storage::disk('public')->put($filename, File::get($file));
                $orderFile->order_id = $order->id;
                $orderFile->url = $filename;
                $orderFile->save();
            }
        }
        //End Store Image

        if($request->sample_article_id) {
            $addRow = new Order();
            $addRow = $addRow->addRowToStockHistory($request, $order->id);
        }
        else {
            $addRow = Client::where('id', $request->client_id)->first()->client;
        }

        //dd($addRow);

        flash('Encomenda do Cliente: '. $addRow . ', com o identificador: '. $order->client_identifier . ' foi criado com sucesso!')->success();

        return redirect()->action('OrderController@index');
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

        $sampleArticles = SampleArticle::all();

        $clients = Client::all();

        $order = Order::find($id);

        $statuses = OrderStatus::all();

        $orderFiles = OrderFile::where('order_id', $id)->get();
//dd($orderFiles);
        return view('orders.create', compact('sampleArticles', 'clients', 'order', 'orderFiles', 'statuses'));
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
        Auth::user()->authorizeRoles(['1', '4']);

        $order= Order::find($id);
        $order->status_id = $request->status_id;
        $order->sample_article_id =  $request->sample_article_id;
        $order->client_id =  $request->client_id;
        $order->client_identifier = $request->client_identifier;
        $order->delivery_date = $request->delivery_date;
        $order->description = $request->description;
        $order->cor1 = $request->cor1;
        $order->cor2 = $request->cor2;
        $order->cor3 = $request->cor3;
        $order->cor4 = $request->cor4;
        $order->tamanho11 = $request->tamanho11;
        $order->tamanho12 = $request->tamanho12;
        $order->tamanho13 = $request->tamanho13;
        $order->tamanho14 = $request->tamanho14;
        $order->tamanho21 = $request->tamanho21;
        $order->tamanho22 = $request->tamanho22;
        $order->tamanho23 = $request->tamanho23;
        $order->tamanho24 = $request->tamanho24;
        $order->tamanho31 = $request->tamanho31;
        $order->tamanho32 = $request->tamanho32;
        $order->tamanho33 = $request->tamanho33;
        $order->tamanho34 = $request->tamanho34;
        $order->tamanho41 = $request->tamanho41;
        $order->tamanho42 = $request->tamanho42;
        $order->tamanho43 = $request->tamanho43;
        $order->tamanho44 = $request->tamanho44;
        $order->save();

        //Store Image
        if(@$request->order_files_id) {
            foreach ($request->order_files_id as $order_files_id) {
                $orderFile = new OrderFile();
                $file = $order_files_id;
                if ($file) {
                    $filename = 'orders/' . explode('.', $file->getClientOriginalName())[0] . '-' . Carbon::now('Europe/London')->format('YmdHis') . '.jpg';
                    Storage::disk('public')->put($filename, File::get($file));
                    $orderFile->order_id = $order->id;
                    $orderFile->url = $filename;
                    $orderFile->save();
                }
            }
        }
        //End Store Image
        //Files to Delete
        if(@$request->filesToDelete) {
            $ids = substr($request->filesToDelete, 0, strlen($request->filesToDelete)-1);
            $ids = explode(',', $ids);
            $order_files = DB::table('order_files')
                ->whereIn('id', $ids)->delete();
        }
        //End Files to Delete

//        dd($request->all());
        if($request->sample_article_id) {
        $addRow = new Order();
        $addRow = $addRow->addRowToStockHistory($request, $id);
        }
        else {
            $addRow = Client::where('id', $request->client_id)->first()->client;
        }
        //return($addRow);

        flash('Encomenda do Cliente '. $addRow . ' com o identificador '. $order->client_identifier . ' foi atualizada com sucesso!')->success();

        return redirect()->action('OrderController@index');
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

        $order = Order::find($id);

        $order_files = OrderFile::where('order_id', $id);

        $order_files->delete();

        $order->delete();

        flash('A encomenda com o id de cliente '. $order->client_identifier . ' e com a descrição '. $order->description . ' foi eliminada com sucesso!')->success();

        return redirect()->action('OrderController@index');
    }
}
