@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{@$client->client ? 'Atualizar Cliente' : 'Criar Novo Cliente'}}</h2><br/>
        <form method="post" action="{{@$client->client ? url('clients/update/'.$client->id) : url('clients/create')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="client">Nome do Cliente:</label>
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
                    <input type="number" class="form-control" name="nif" maxlength="9" value="{{@$client->nif}}" onKeyDown="limitText(this,9);" required>
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
                    <button type="submit" class="btn btn-success">{{@$client->client ? 'Atualizar' : 'Criar'}}</button>
                    <button type="button" onclick="window.history.back();" class="btn btn-info">Voltar</button>
                </div>
            </div>
        </form>
    </div>

    <script language="javascript" type="text/javascript">
        function limitText(limitField, limitNum) {
            if (limitField.value.length > limitNum) {
                limitField.value = limitField.value.substring(0, limitNum);
            }
        }
    </script>
@endsection