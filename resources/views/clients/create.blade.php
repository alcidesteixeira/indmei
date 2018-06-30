@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{@$client->client ? 'Atualizar Fornecedor' : 'Criar Novo Fornecedor'}}</h2><br/>
        <form method="post" action="{{@$client->client ? url('clients/update/'.$client->id) : url('clients/create')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="client">Nome do Fornecedor:</label>
                    <input type="text" class="form-control" name="client" value="{{@$client->client}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" value="{{@$client->email}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="nif">NIF:</label>
                    <input type="text" class="form-control" name="nif" value="{{@$client->nif}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="Description">Descrição:</label>
                    <input type="text" class="form-control" name="description" value="{{@$client->description}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6" style="margin-top:60px">
                    <button type="submit" class="btn btn-success">{{@$client->supplier ? 'Atualizar' : 'Criar'}}</button>
                    <button type="button" onclick="window.history.back();" class="btn btn-info">Voltar</button>
                </div>
            </div>
        </form>
    </div>
@endsection