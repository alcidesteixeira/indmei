<?php

namespace App\Http\Controllers;

use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->authorizeRoles(['1', '4']);

        $suppliers = Supplier::all();

        return view('suppliers.list', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->authorizeRoles(['1', '4']);

        return view('suppliers.create');
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

        $supplier= new Supplier();
        $supplier->supplier = $request->supplier;
        $supplier->email = $request->email;
        $supplier->nif = $request->nif;
        $supplier->description = $request->description;
        $supplier->save();

        flash('Fornecedor '. $supplier->supplier . ' foi criado com sucesso!')->success();

        return redirect()->action('SupplierController@index');
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

        $supplier = Supplier::find($id);
        return view('suppliers.create', compact('supplier','id'));
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

        $supplier= Supplier::find($id);
        $supplier->supplier = $request->supplier;
        $supplier->email = $request->email;
        $supplier->nif = $request->nif;
        $supplier->description = $request->description;
        $supplier->updated_at = Carbon::now('Europe/Lisbon');
        $supplier->save();

        flash('Fornecedor '. $supplier->supplier . ' foi atualizado com sucesso!')->success();

        return redirect()->action('SupplierController@index');
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

        $supplier = Supplier::find($id);

        $ordersWithThisSupplier = Supplier::find($id)->orders()->first();
        //dd($usersWithThisRole);
        if($ordersWithThisSupplier) {

            flash('Atenção! O Fornecedor '. $supplier->supplier . ' não pode ser eliminado pois está associado a alguma encomenda! <br> Altere o fornecedor da encomenda antes de poder apagar o fornecedor!')->error();

        }
        else {

            $supplier->delete();

            flash('Fornecedor '. $supplier->supplier . ' foi eliminado com sucesso!')->success();
        }

        return redirect()->action('SupplierController@index');
    }
}
