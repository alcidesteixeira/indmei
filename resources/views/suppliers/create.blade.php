@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{@$supplier->supplier ? 'Atualizar Fornecedor' : 'Criar Novo Fornecedor'}}</h2><br/>
        <form method="post" action="{{@$supplier->supplier ? url('suppliers/update/'.$supplier->id) : url('suppliers/create')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="supplier">Nome:</label>
                    <input type="text" class="form-control" name="supplier" value="{{@$supplier->supplier}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" value="{{@$supplier->email}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="nif">NIF:</label>
                    <input type="number" class="form-control" name="nif" maxlength="8" value="{{@$supplier->nif}}" onKeyDown="limitText(this,8);" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="Description">Descrição:</label>
                    <input type="text" class="form-control" name="description" value="{{@$supplier->description}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6" style="margin-top:60px">
                    <button type="submit" class="btn btn-success">{{@$supplier->supplier ? 'Atualizar' : 'Criar'}}</button>
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