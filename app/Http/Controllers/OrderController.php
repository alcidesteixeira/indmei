<?php

namespace App\Http\Controllers;

use App\Client;
use App\Mail\sendSimpleEmail;
use App\Order;
use App\OrderFile;
use App\Role;
use App\SampleArticle;
use App\OrderStatus;
use App\SampleArticleGuiafio;
use App\SampleArticleStep;
use App\SampleArticlesWire;
use App\WarehouseProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
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
        Auth::user()->authorizeRoles(['1', '3', '4', '6', '7']);

        $orders = Order::where('status_id', '<>', 8)->where('status_id', '<>', 7)->get();

        $deleted_orders = Order::where('status_id', 7)->get();

        $view = 'orders';

        return view('orders.list', compact('orders', 'deleted_orders', 'view'));
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

        $highestID = DB::table('orders')->max('id');
        $highestID = $highestID+1;

        return view('orders.create', compact('sampleArticles', 'clients', 'statuses', 'highestID'));
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

        Auth::user()->authorizeRoles(['1', '4']);

        if(@$request->order_files_id) {
            foreach ($request->order_files_id as $order_files_id) {
                if (!str_contains($order_files_id->getClientOriginalName(), ['pdf', 'jpg', 'png', 'gif', 'JPG', 'PNG', 'GIF'])) {
                    flash('Por favor, insira uma extensão válida. (.pdf, .jpeg, .png, .gif)')->error();

                    return redirect()->back();
                }
            }
        }

        //dd($request->all());
        $highestID = DB::table('orders')->max('id');
        $highestID = $highestID+1;

        $order= new Order();
        $order->user_id =  Auth::id();
        $order->status_id = $request->status_id;
        $order->sample_article_id =  $request->sample_article_id;
        $order->client_id =  $request->client_id;
        $order->client_identifier = date('Y').'-'.$highestID;
        $order->client_identifier_public = $request->client_identifier_public;
        $order->delivery_date = $request->delivery_date;
        $order->description = $request->description;
        $order->cor1 = $request->cor1;
        $order->cor2 = $request->cor2;
        $order->cor3 = $request->cor3;
        $order->cor4 = $request->cor4;
        $order->tamanho1 = $request->tamanho1;
        $order->tamanho2 = $request->tamanho2;
        $order->tamanho3 = $request->tamanho3;
        $order->tamanho4 = $request->tamanho4;
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
        if(!empty($order->filesToDelete)) {
            foreach ($request->order_files_id as $order_files_id) {
                $orderFile = new OrderFile();
                $file = $order_files_id;
                if ($file) {
                    $extension = str_contains($file->getClientOriginalName(), 'pdf') ? '.pdf' : '.jpg';
                    $filename = 'orders/' . explode('.', $file->getClientOriginalName())[0] . '-' . Carbon::now('Europe/London')->format('YmdHis') . $extension;
                    Storage::disk('public')->put($filename, File::get($file));
                    $orderFile->order_id = $order->id;
                    $orderFile->url = $filename;
                    $orderFile->save();
                }
            }
        }
        //End Store Image
        if($request->sample_article_id) {
            $request->request->add(['client_identifier' => date('Y').'-'.$highestID]);
            $addRow = new Order();
            $addRow = $addRow->addRowToStockHistory($request, $order->id);
        }
        else {
            $addRow = Client::where('id', $request->client_id)->first()->client;
        }

        //dd($addRow);


        //Enviar email para criadores de amostras para avisar que uma encomenda necessita de amostra - 2
        //Para avisar que uma encomenda necessita de orçamento - 3
        //Para avisar que uma encomenda está pronta para produção - 5
        /*switch ($request->status_id) {
            case 2:
                //Realizar amostra é necessária
                $users = Role::find(3)->users()->orderBy('name')->get();
                $subject = "Nova encomenda à espera de amostra.";
                $body = "Tem uma nova encomenda que está a aguarda a criação de uma amostra:
                        <br>Nome do Cliente: ". $addRow ."
                        <br>Identificador do Cliente: ". $request->client_identifier ."
                        <br>Data de entrega da encomenda: ". $request->delivery_date ."
                        <br>Descrição da encomenda: ". $request->description ."
                        <br><br>
                        Para aceder à encomenda, dirija-se à plataforma, ou clique
                        <a href='".url("/orders/edit/{$order->id}")."' target='_blank'>aqui</a>.";
                foreach($users as $user) {
                    Mail::to($user->email)->send(new sendSimpleEmail($subject, $body));
                }
                break;
            case 3:
                //Realizar orçamento é necessário
                //Realizar amostra é necessária
                $users = Role::find(7)->users()->orderBy('name')->get();
                $subject = "Nova encomenda à espera de orçamentação.";
                $body = "Tem uma nova encomenda que está a aguarda o envio de orçamentação:
                        <br>Nome do Cliente: ". $addRow ."
                        <br>Identificador do Cliente: ". $request->client_identifier ."
                        <br>Data de entrega da encomenda: ". $request->delivery_date ."
                        <br>Descrição da encomenda: ". $request->description ."
                        <br><br>
                        Para aceder à encomenda, dirija-se à plataforma, ou clique
                        <a href='".url("/orders/edit/{$order->id}")."' target='_blank'>aqui</a>.";
                foreach($users as $user) {
                    Mail::to($user->email)->send(new sendSimpleEmail($subject, $body));
                }
                break;
            case 5:
                //Encomenda enviada para produção
                $users = Role::find(6)->users()->orderBy('name')->get();
                $subject = "Nova encomenda entrou em produção.";
                $body = "Tem uma nova encomenda que está pronta a ser produzida:
                        <br>Nome do Cliente: ". $addRow ."
                        <br>Identificador do Cliente: ". $request->client_identifier ."
                        <br>Data de entrega da encomenda: ". $request->delivery_date ."
                        <br>Descrição da encomenda: ". $request->description ."
                        <br><br>
                        Para aceder à encomenda, dirija-se à plataforma, ou clique
                        <a href='".url("/orders/list/")."' target='_blank'>aqui</a>.";
                foreach($users as $user) {
                    Mail::to($user->email)->send(new sendSimpleEmail($subject, $body));
                }
                break;
        } */
        //Fim de envio de emails

        session_start();
        $_SESSION["update_warehouse"] = true;

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
        Auth::user()->authorizeRoles(['1', '3', '4', '7']);

        $sampleArticles = SampleArticle::all();

        $clients = Client::all();

        $order = Order::find($id);

        $statuses = OrderStatus::all();

        $orderFiles = OrderFile::where('order_id', $id)->get();
//
//        $steps = SampleArticleStep::all();
//
//        $warehouseProducts = WarehouseProduct::all();
//
//        $guiafios = SampleArticleGuiafio::all();
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

        if(@$request->order_files_id) {
            foreach ($request->order_files_id as $order_files_id) {
                //dd($order_files_id);
                if (!str_contains($order_files_id->getClientOriginalName(), ['pdf', 'jpg', 'png', 'gif', 'JPG', 'PNG', 'GIF'])) {
                    flash('Por favor, insira uma extensão válida. (.pdf, .jpeg, .png, .gif)')->error();

                    return redirect()->back();
                }
            }
        }

        $order= Order::find($id);
        $order->status_id = $request->status_id;
        $order->sample_article_id =  $request->sample_article_id;
        $order->client_id =  $request->client_id;
        $order->client_identifier_public = $request->client_identifier_public;
        $order->delivery_date = $request->delivery_date;
        $order->description = $request->description;
        $order->cor1 = $request->cor1;
        $order->cor2 = $request->cor2;
        $order->cor3 = $request->cor3;
        $order->cor4 = $request->cor4;
        $order->tamanho1 = $request->tamanho1;
        $order->tamanho2 = $request->tamanho2;
        $order->tamanho3 = $request->tamanho3;
        $order->tamanho4 = $request->tamanho4;
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
                if($file) {
                    $extension = str_contains($file->getClientOriginalName(), 'pdf') ? '.pdf' : '.jpg';
                    $filename = 'orders/' . explode('.', $file->getClientOriginalName())[0] . '-' . Carbon::now('Europe/London')->format('YmdHis') . $extension;
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

        //Enviar email para criadores de amostras para avisar que uma encomenda necessita de amostra - 2
        //Para avisar que uma encomenda necessita de orçamento - 3
        //Para avisar que uma encomenda está pronta para produção - 5
        /*switch ($request->status_id) {
            case 2:
                //Realizar amostra é necessária
                $users = Role::find(3)->users()->orderBy('name')->get();
                $subject = "Nova encomenda à espera de amostra.";
                $body = "Tem uma nova encomenda que está a aguarda a criação de uma amostra:
                        <br>Nome do Cliente: ". $addRow ."
                        <br>Identificador do Cliente: ". $request->client_identifier ."
                        <br>Data de entrega da encomenda: ". $request->delivery_date ."
                        <br>Descrição da encomenda: ". $request->description ."
                        <br><br>
                        Para aceder à encomenda, dirija-se à plataforma, ou clique
                        <a href='".url("/orders/edit/{$order->id}")."' target='_blank'>aqui</a>.";
                foreach($users as $user) {
                    Mail::to($user->email)->send(new sendSimpleEmail($subject, $body));
                }
                break;
            case 3:
                //Realizar orçamento é necessário
                //Realizar amostra é necessária
                $users = Role::find(7)->users()->orderBy('name')->get();
                $subject = "Nova encomenda à espera de orçamentação.";
                $body = "Tem uma nova encomenda que está a aguarda o envio de orçamentação:
                        <br>Nome do Cliente: ". $addRow ."
                        <br>Identificador do Cliente: ". $request->client_identifier ."
                        <br>Data de entrega da encomenda: ". $request->delivery_date ."
                        <br>Descrição da encomenda: ". $request->description ."
                        <br><br>
                        Para aceder à encomenda, dirija-se à plataforma, ou clique
                        <a href='".url("/orders/edit/{$order->id}")."' target='_blank'>aqui</a>.";
                foreach($users as $user) {
                    Mail::to($user->email)->send(new sendSimpleEmail($subject, $body));
                }
                break;
            case 5:
                //Encomenda enviada para produção
                $users = Role::find(6)->users()->orderBy('name')->get();
                $subject = "Nova encomenda entrou em produção.";
                $body = "Tem uma nova encomenda que está pronta a ser produzida:
                        <br>Nome do Cliente: ". $addRow ."
                        <br>Identificador do Cliente: ". $request->client_identifier ."
                        <br>Data de entrega da encomenda: ". $request->delivery_date ."
                        <br>Descrição da encomenda: ". $request->description ."
                        <br><br>
                        Para aceder à encomenda, dirija-se à plataforma, ou clique
                        <a href='".url("/orders/list/")."' target='_blank'>aqui</a>.";
                foreach($users as $user) {
                    Mail::to($user->email)->send(new sendSimpleEmail($subject, $body));
                }
                break;
        }*/
        //fim de envio de emails

        session_start();
        $_SESSION["update_warehouse"] = true;

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
        $order->status_id = 8;
        $order->save();

//        $order_files = OrderFile::where('order_id', $id);

//        $order_files->delete();

//        $order->delete();

        flash('A encomenda com o id de cliente '. $order->client_identifier . ' e com a descrição '. $order->description . ' foi eliminada com sucesso!')->success();

        return redirect()->action('OrderController@index');
    }
}
