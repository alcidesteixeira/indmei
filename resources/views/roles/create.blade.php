@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{@$role->name ? 'Atualizar Role' : 'Criar Novo Role'}}</h2><br/>
        <form method="post" action="{{@$role->name ? url('roles/update/'.$role->id) : url('roles/create')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="Name">Nome:</label>
                    <input type="text" class="form-control" name="name" value="{{@$role->name}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="Description">Descrição:</label>
                    <input type="text" class="form-control" name="description" value="{{@$role->description}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6" style="margin-top:60px">
                    <button type="submit" class="btn btn-success">{{@$role->name ? 'Atualizar' : 'Criar'}}</button>
                    <button type="button" onclick="window.history.back();" class="btn btn-info">Voltar</button>
                </div>
            </div>
        </form>
    </div>
@endsection