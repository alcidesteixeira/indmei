@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Editar Utilizador: {{@$user->name}}</h2><br/>
        <form method="post" action="{{url('roles/attribute/edit/'.$user->id)}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="Name">Nome:</label>
                    <input type="text" class="form-control" name="name" value="{{@$user->name}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="Description">Email:</label>
                    <input type="email" class="form-control" name="email" value="{{@$user->email}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="Description">Permissões Selecionadas:</label>
                    @foreach($roles as $role)
                    <div class="checkbox">
                        <label><input type="checkbox" name="{{$role->id}}" value="{{$role->id}}" {{in_array($role->id, $userRoles) ? 'checked' : ''}}>{{$role->name}}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6" style="margin-top:60px">
                    <button type="submit" class="btn btn-success">Atualizar</button>
                    <button type="button" onclick="window.history.back();" class="btn btn-info">Voltar</button>
                </div>
            </div>
        </form>
    </div>
@endsection