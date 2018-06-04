<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->authorizeRoles(['1']);

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
        Auth::user()->authorizeRoles(['1']);

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
        Auth::user()->authorizeRoles(['1']);

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
        Auth::user()->authorizeRoles(['1']);

        $role = Role::find($id);
        return view('roles.create', compact('role','id'));
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
        Auth::user()->authorizeRoles(['1']);

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
        Auth::user()->authorizeRoles(['1']);

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

    /**
     * Get users and roles associated
     */
    public function attributeRoles ()
    {

        Auth::user()->authorizeRoles(['1']);

        $user = new User();
        $users = $user->getAllUser();

        return view('roles.attribute_roles',compact('users', 'roles'));
    }

    /**
     * Edit user and attributed roles
     */
    public function editAttributeRoles ($id)
    {

        Auth::user()->authorizeRoles(['1']);

        $user = User::find($id);
        $roles = Role::all();

        $rolesFromUser = $user->roles()->get();
        $userRoles = [];
        foreach ($rolesFromUser as $role) {
            array_push($userRoles, $role->id);
        }

        return view('roles.edit_attributes',compact('user', 'roles', 'userRoles'));

    }

    /**
     * Store user and attributed roles
     */
    public function storeAttributeRoles (Request $request, $id)
    {

        Auth::user()->authorizeRoles(['1']);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->updated_at = Carbon::now('Europe/Lisbon');
        $user->save();

        $countRoles = count(Role::all());
        $user->roles()->detach();
        for($i = 1; $i <= $countRoles; $i ++) {
            if($request->$i) {
                $user->roles()->attach(Role::where('id', $i)->first());
            }
            else {
                $user->roles()->detach(Role::where('id', $i)->first());
            }
        }
        flash('O utilizador '. $user->name . ' foi atualizado com sucesso!')->success();


        return redirect()->action('RoleController@attributeRoles');
    }

    /**
     * Delete User
     */
    public function deleteAttributeRoles($id)
    {

        Auth::user()->authorizeRoles(['1']);

        $user = User::find($id);

        $user->roles()->detach();

        $user->delete();

        flash('O Utilizador '. $user->name . ' foi eliminado com sucesso!')->success();

        return redirect()->action('RoleController@attributeRoles');
    }
}
