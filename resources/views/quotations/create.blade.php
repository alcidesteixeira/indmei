@extends('layouts.app')

@section('content')
    <style>
        button a, a:hover {
            color: #fff;
            text-decoration: none;
        }
    </style>


    <div class="container">
        <h2>{{@$order->client_identifier ? 'Atualizar Orçamentação' : 'Criar Orçamentação'}}</h2><br/>
        <form method="post" action="{{@$quotation->order_id ? url('quotation/update/'.$quotation->id) : url('quotation/create')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="client_id">Nome do Cliente:</label>
                    <input type="text" class="form-control" value="{{@$order->client->client}}" required readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="client_identifier">Identificador do Cliente:</label>
                    <input type="text" class="form-control" value="{{@$order->client_identifier}}" required readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="Description">Descrição:</label>
                    <input type="text" class="form-control" value="{{@$order->description}}" required readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="delivery_date">Data de entrega:</label>
                    <input type="date" class="form-control" value="{{@$order->delivery_date}}" required readonly>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label style="padding-right: 20px;">Identificador INDMEI:</label>
                        <button type="button" class="btn btn-info"><a href="{{url('/samples/edit/'.@$order->sample_article_id)}}" target="_blank">Ver Amostra de Artigo</a></button>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="orderCost">Custo da Amostra:</label>
                    <input type="number" class="form-control" name="orderCost" value="" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="tag">Custo das etiquetas:</label>
                    <input type="number" class="form-control" name="tag" value="" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="boxes">Custo das Caixas:</label>
                    <input type="number" class="form-control" value="" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="defect">% de defeito:</label>
                    <input type="number" class="form-control" name="defect" value="" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="manpower">Mão-de-Obra:</label>
                    <input type="number" class="form-control" name="manpower" value="" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="otherCosts">Outros Custos:</label>
                    <input type="number" class="form-control" name="otherCosts" value="" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="total">Total:</label>
                    <input type="number" class="form-control" name="total" value="" required readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="totalSent">Total a Enviar:</label>
                    <input type="number" class="form-control" name="totalSent" value="" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6" style="margin-top:60px;margin-bottom:40px;">
                    <button type="submit" class="btn btn-success">{{@$quotation->order_id ? 'Atualizar' : 'Criar'}}</button>
                    <button type="button" onclick="window.history.back();" class="btn btn-info">Voltar</button>
                </div>
            </div>
        </form>
    </div>
@endsection