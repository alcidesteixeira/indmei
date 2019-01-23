@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @if(@$content)
                        <div class="card-header">Enviar Orçamentação</div>
                    @elseif(@$prodSpecArray)
                        <div class="card-header">Fazer pedido de Stock</div>
                    @else
                        <div class="card-header">Criar novo Email</div>
                    @endif

                    <div class="card-body">
                        <form method="post" action="{{url('email/send/')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="sel1">Enviar para</label>
                                <select class="form-control" id="client" name="client">
                                    <option value="0">Novo</option>
                                    <optgroup label="Utilizadores">
                                        @foreach($users as $user)
                                            <option value="{{$user->email}}">{{$user->name}} ({{$user->email}})</option>
                                        @endforeach
                                    </optgroup>
                                    @if(!@$prodSpecArray)
                                    <optgroup label="Clientes">
                                        @foreach($clients as $client)
                                        <option value="{{$client->email}}">{{$client->client}} ({{$client->email}})</option>
                                        @endforeach
                                    </optgroup>
                                    @endif
                                    <optgroup label="Fornecedores">
                                        @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->email}}">{{$supplier->client}} ({{$supplier->email}})</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="usr">Novo Endereço de envio:</label>
                                <input type="email" class="form-control" id="new_address" name="new_address">
                            </div>
                            <div class="form-group">
                                <label for="usr">Assunto:</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            @if(@$prodSpecArray)
                            <div class="form-group">
                                <label for="usr">Quantidade a pedir (Kg):</label>
                                <input type="number" class="form-control" min="1" name="amountStockRequested" required>
                                <input type="hidden" name="id" value="{{$stock_id}}">
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="comment">Conteúdo:</label>
                                @if(@$content)
                                    <div class="form-control" id="bodyQuotation" contenteditable="true">{!! @$content !!}</div>
                                @elseif(@$prodSpecArray)
                                    <div class="form-control" id="bodyContent" contenteditable="true">@foreach($prodSpecArray as $key => $val){!!$key.': '.$val.'; <br>' !!}@endforeach</div>
                                @else
                                    <textarea class="form-control" rows="5" id="bodyEmail" required></textarea>
                                @endif
                            </div>
                            <input type="hidden" name="body2" id="body2">
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="form-group col-md-6" style="margin-top:20px;margin-bottom:20px;">
                                    <button type="submit" style="float:left" class="btn btn-success col-md-5">Enviar</button>
                                    <button type="button" style="float:right" onclick="window.history.back();" class="btn btn-info col-md-5">Voltar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $( document ).ready( function () {
            $("form").submit ( function (e) {
                let client = $("#client").val();
                let newAddress = $("#new_address").val();
                if(client == 0 && newAddress == '') {
                    alert("Deve selecionar pelo menos um endereço de envio de email.");
                    e.preventDefault();
                }
                //Before submit get html code of textarea
                if("{!! @$content !!}" !== "") {
                    let body = $("#bodyQuotation").html().replace(/\n|\r/g,'<br />');
                    $("#body2").val(body);
                }
                else if("{!! @$prodSpecArray['cor'] !!}" !== "") {
                    let body = $("#bodyContent").html().replace(/\n|\r/g,'<br />');
                    $("#body2").val(body);
                }
                else {
                    let body = $("#bodyEmail").val().replace(/\n|\r/g,'<br />');
                    $("#body2").val(body);
                }
            });
        });
    </script>
@endsection