@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{@$order->client_identifier ? 'Atualizar Encomenda' : 'Criar Nova Encomenda'}}</h2><br/>
        <form method="post" action="{{@$order->client_identifier ? url('orders/update/'.$order->id) : url('orders/create')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="client_id">Nome do Cliente:</label>
                    <select class="form-control" name="client_id" id="">
                        @foreach($clients as $client)
                        <option value="{{$client->id}}">{{$client->client}}</option>
                        @endforeach
                    </select>
                    {{--<input type="text" class="form-control" name="client" value="{{@$order->client_id}}" required>--}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="client_identifier">Identificador do Cliente:</label>
                    <input type="text" class="form-control" name="client_identifier" value="{{@$order->client_identifier}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="Description">Descrição:</label>
                    <input type="text" class="form-control" name="description" value="{{@$order->description}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="order_files_id">Upload Ficheiros:</label>
                    <input type="file" class="form-control-file" name="order_files_id[]" value="{{@$order->order_files_id}}" multiple >
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="delivery_date">Data de entrega:</label>
                    <input type="date" class="form-control" name="delivery_date" value="{{@$order->delivery_date}}" required>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="Description">Identificador INDMEI:</label>
                    <select class="form-control" name="sample_article_id" id="sampleArticleChange">
                        <option value=""></option>
                        @foreach($sampleArticles as $sample)
                        <option value="{{$sample->id}}">{{$sample->reference}}</option>
                        @endforeach
                    </select>
                    {{--<input type="text" class="form-control" name="sample_article_id" value="{{@$order->sample_article_id}}">--}}
                </div>
            </div>
            <table class="table table-striped thead-dark">
                <thead>
                <tr>
                    <th></th>
                    <th><input type="text" class="form-control" name="cor1" value="{{@$order->cor1}}"></th>
                    <th><input type="text" class="form-control" name="cor2" value="{{@$order->cor2}}"></th>
                    <th><input type="text" class="form-control" name="cor3" value="{{@$order->cor3}}"></th>
                    <th><input type="text" class="form-control" name="cor4" value="{{@$order->cor4}}"></th>
                </tr>
                </thead>
                <tbody>

                @for($i = 1; $i < 5; $i++)
                    @php $tamanho1 = "tamanho1".$i; $tamanho2 = "tamanho2".$i; $tamanho3 = "tamanho3".$i; $tamanho4 = "tamanho4".$i; @endphp
                    <tr>
                        <td data-col1="Tamanho">
                            <span type="text" name="tamanho{{$i}}" id="tamanho{{$i}}" value=""></span>
                        </td>
                        <td data-col2="Cor1">

                            <input type="text" name="tamanho1{{$i}}" id="tamanho1{{$i}}" class="sizes form-control" value="{{@$order->$tamanho1 ? @$order->$tamanho1 : 0}}">
                        </td>
                        <td data-col3="Cor2">
                            <input type="text" name="tamanho2{{$i}}" id="tamanho2{{$i}}" class="sizes form-control" value="{{@$order->$tamanho2 ? @$order->$tamanho2 : 0}}">
                        </td>
                        <td data-col4="Cor3">
                            <input type="text" name="tamanho3{{$i}}" id="tamanho3{{$i}}" class="sizes form-control" value="{{@$order->$tamanho3 ? @$order->$tamanho3 : 0}}">
                        </td>
                        <td data-col5="Cor4">
                            <input type="text" name="tamanho4{{$i}}" id="tamanho4{{$i}}" class="sizes form-control" value="{{@$order->$tamanho4 ? @$order->$tamanho4 : 0}}">
                        </td>
                    </tr>
                @endfor
                    <tr>
                        <td data-col1="">
                            Pedido
                        </td>
                        <td data-col2="Pedido da Cor1" id="pedido1">
                            {{@$order->$tamanho11 + @$order->$tamanho12 +@$order->$tamanho13 +@$order->$tamanho14}}
                        </td>
                        <td data-col3="Pedido da Cor2" id="pedido2">
                            {{@$order->$tamanho21 + @$order->$tamanho22 +@$order->$tamanho23 +@$order->$tamanho24}}
                        </td>
                        <td data-col4="Pedido da Cor3" id="pedido3">
                            {{@$order->$tamanho31 + @$order->$tamanho32 +@$order->$tamanho33 +@$order->$tamanho34}}
                        </td>
                        <td data-col5="Pedido da Cor4" id="pedido4">
                            {{@$order->$tamanho41 + @$order->$tamanho42 +@$order->$tamanho43 +@$order->$tamanho44}}
                        </td>
                    </tr>
                    <tr>
                        <td data-col1="">
                            Em Falta
                        </td>
                        <td data-col2="Em Falta da Cor1" id="falta1">
                        </td>
                        <td data-col3="Em Falta da Cor2" id="falta2">
                        </td>
                        <td data-col4="Em Falta da Cor3" id="falta3">
                        </td>
                        <td data-col5="Em Falta da Cor4" id="falta4">
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6" style="margin-top:60px;margin-bottom:40px;">
                    <button type="submit" class="btn btn-success">{{@$order->client_identifier ? 'Atualizar' : 'Criar'}}</button>
                    <button type="button" onclick="window.history.back();" class="btn btn-info">Voltar</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $("#sampleArticleChange").change( function () {
            let id = $(this).val();
            $.ajax({
                url: "/orders/getSampleArticleId/"+id,
                success: function(result){
                    $("#tamanho1").text(result['tamanho1']);
                    $("#tamanho2").text(result['tamanho2']);
                    $("#tamanho3").text(result['tamanho3']);
                    $("#tamanho4").text(result['tamanho4']);
                }
            });
        });

        $(".sizes").keyup( function () {
            $("#pedido1").text(parseInt($("#tamanho11").val())+parseInt($("#tamanho12").val())+parseInt($("#tamanho13").val())+parseInt($("#tamanho14").val()));
            $("#pedido2").text(parseInt($("#tamanho21").val())+parseInt($("#tamanho22").val())+parseInt($("#tamanho23").val())+parseInt($("#tamanho24").val()));
            $("#pedido3").text(parseInt($("#tamanho31").val())+parseInt($("#tamanho32").val())+parseInt($("#tamanho33").val())+parseInt($("#tamanho34").val()));
            $("#pedido4").text(parseInt($("#tamanho41").val())+parseInt($("#tamanho42").val())+parseInt($("#tamanho43").val())+parseInt($("#tamanho44").val()));
        });
    </script>
@endsection