<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use Carbon\Carbon;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        return view('roles.list', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $role= new Role();
        $role->name = $request->get('name');
        $role->description = $request->get('description');
        $role->save();

        flash('Role '. $role->name . ' foi criado com sucesso!')->success();

        return redirect()->action('RoleController@index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        return view('roles.create',compact('role','id'));
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
        $role= Role::find($id);
        $role->name = $request->get('name');
        $role->description = $request->get('description');
        $role->updated_at = Carbon::now('Europe/Lisbon');
        $role->save();

        flash('Role '. $role->name . ' foi atualizado com sucesso!')->success();

        return redirect()->action('RoleController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        $usersWithThisRole = Role::find($id)->users()->first();
        //dd($usersWithThisRole);
        if($usersWithThisRole) {

            flash('Atenção! O Role '. $role->name . ' não pode ser eliminado pois está associado a algum utilizador! <br> Altere o role do utilizador antes de poder apagar o Role!')->error();

        }
        else {

            $role->delete();

            flash('Role '. $role->name . ' foi eliminado com sucesso!')->success();
        }

        return redirect()->action('RoleController@index');
    }
}
