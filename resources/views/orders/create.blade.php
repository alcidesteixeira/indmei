@extends('layouts.app')

@section('content')

    <link href="{{ asset('css/steps.css') }}" rel="stylesheet">
    <script src="{{ asset('js/steps.js') }}"></script>


    <div class="container">
        @include('flash::message')
        <h2>{{@$order->client_identifier ? 'Atualizar Encomenda' : 'Criar Nova Encomenda'}}</h2><br/>
        <form method="post" id="sendOrder" action="{{@$order->client_identifier ? url('orders/update/'.$order->id) : url('orders/create')}}" enctype="multipart/form-data">
            @csrf
            {{--Envio do status para desktop e mobile comum--}}
            <input type="hidden" name="status_id" id="status_id">
            <div class="row display-small">
                <div class="form-group col-sm-6">
                    <label for="status_id">Status:</label>
                    <select class="form-control" id="viaSelect">
                        @foreach($statuses as $status)
                            @if(in_array($status->id, [1, 5, 6, 7]))
                            <option value="{{$status->id}}" {{$status->id == @$order->status_id ? 'selected' : ''}}>{{$status->status}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row display-big" style="margin-bottom: 20px;">
                <label for="Status" style="margin-bottom: 40px;">Status:</label>
                <div class="steps-form-2">
                    <div class="steps-row-2 setup-panel-2 d-flex justify-content-between">
                        <div class="steps-step-2">
                            <a href="#" id="step-1" data-id="1" type="button" class="btn btn-blue-grey btn-highlighted waves-effect ml-0" data-toggle="tooltip" data-placement="top" title="Por Produzir">
                                <i class="fas fa-folder-open"></i></a>
                            <p style="margin-left: -10px">Por Produzir</p>
                        </div>
                        <div class="steps-step-2" style="display:none">
                            <a href="#" id="step-2" data-id="2" type="button" class="btn btn-blue-grey {{@$order->status_id < 2 ? 'btn-circle-2' : 'btn-highlighted' }}  waves-effect" data-toggle="tooltip" data-placement="top" title="A Produzir Amostra">
                                <i class="fas fa-shoe-prints"></i></a>
                        </div>
                        <div class="steps-step-2" style="display:none">
                            <a href="#" id="step-3" data-id="3" type="button" class="btn btn-blue-grey {{@$order->status_id < 3 ? 'btn-circle-2' : 'btn-highlighted' }} waves-effect" data-toggle="tooltip" data-placement="top" title="A Criar Orçamento">
                                <i class="fas fa-money-bill-wave"></i></a>
                        </div>
                        <div class="steps-step-2" style="display:none">
                            <a href="#" id="step-4" data-id="4" type="button" class="btn btn-blue-grey {{@$order->status_id < 4 ? 'btn-circle-2' : 'btn-highlighted' }} waves-effect mr-0" data-toggle="tooltip" data-placement="top" title="A Aguardar Resposta do Cliente">
                                <i class="fas fa-user-tie"></i></a>
                        </div>
                        <div class="steps-step-2">
                            <a href="#" id="step-5" data-id="5" type="button" class="btn btn-blue-grey {{@$order->status_id < 5 ? 'btn-circle-2' : 'btn-highlighted' }} waves-effect mr-0" data-toggle="tooltip" data-placement="top" title="Em Produção">
                                <i class="fab fa-product-hunt"></i></a>
                            <p>Em Produção</p>
                        </div>
                        <div class="steps-step-2">
                            <a href="#" id="step-6" data-id="6" type="button" class="btn btn-blue-grey {{@$order->status_id < 6 ? 'btn-circle-2' : 'btn-highlighted' }} waves-effect mr-0" data-toggle="tooltip" data-placement="top" title="Bloqueada">
                                <i class="fas fa-ban"></i></a>
                            <p>Bloqueada</p>
                        </div>
                        <div class="steps-step-2">
                            <a href="#" id="step-7" data-id="7" type="button" class="btn btn-blue-grey {{@$order->status_id < 7 ? 'btn-circle-2' : 'btn-highlighted' }} waves-effect mr-0" data-toggle="tooltip" data-placement="top" title="Produzido">
                                <i class="fas fa-check" aria-hidden="true"></i></a>
                            <p>Produzida</p>
                        </div>
                        <div class="steps-step-2" style="display:none">
                            <a href="#" id="step-8" data-id="8" type="button" class="btn btn-blue-grey {{@$order->status_id < 8 ? 'btn-circle-2' : 'btn-highlighted' }} waves-effect mr-0" data-toggle="tooltip" data-placement="top" title="Em Distribuição">
                                <i class="fas fa-truck-moving"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @if(@$order->status_id == 7)
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="client_id">Ver definições finais da encomenda produzida:</label>
                    <a href="{{'/images/finishedOrders/finalorder'.@$order->id.'.png'}}" target="_blank">Ver Aqui</a>
                    {{--<input type="text" class="form-control" name="client" value="{{@$order->client_id}}" required>--}}
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="client_id">Nome do Cliente:</label>
                    <select class="form-control" name="client_id" id="">
                        @foreach($clients as $client)
                        <option value="{{$client->id}}" {{@$order->client_id == $client->id ? 'selected' : ''}}>{{$client->client}}</option>
                        @endforeach
                    </select>
                    {{--<input type="text" class="form-control" name="client" value="{{@$order->client_id}}" required>--}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="client_identifier">Nome/Número de Encomenda:</label>
                    <input type="text" class="form-control" name="client_identifier_public" value="{{@$order->client_identifier_public}}" required>
                    <input type="hidden" class="form-control" name="client_identifier" value="{{@$order->client_identifier}}" required disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="Description">Observações:</label>
                    <input type="text" class="form-control" name="description" value="{{@$order->description}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-3">
                    <label for="order_files_id">Upload Ficheiros:</label>
                    <input type="file" class="form-control-file" name="order_files_id[]" value="{{@$order->order_files_id}}" multiple >
                </div>
                @if(@$order->client_identifier)
                <div class="form-group col-md-3">
                    <label for="order_files_id">Ficheiros:</label>
                    @foreach($orderFiles as $file)
                    <p>
                        <a target="_blank" href="../../storage/{{$file->url}}">{{substr($file->url, 7, strlen($file->url)-7)}}</a>
                        <i data-remove="{{$file->id}}" class="far fa-window-close" style="cursor:pointer;"></i>
                    </p>
                    @endforeach
                </div>
                @endif
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
                <div class="col-md-3">
                    <div id="image_sample" style="float:left; margin-right:10px"></div>
                    <div id="image_sample_desc"></div>
                </div>
                <div class="form-group col-md-6">
                    <label for="Description">Amostra INDMEI:</label>
                    <select class="form-control" name="sample_article_id" id="sampleArticleChange">
                        <option value=""></option>
                        @foreach($sampleArticles as $sample)
                        <option data-src="{{$sample->image_url}}" value="{{$sample->id}}" {{$sample->id == @$order->sample_article_id ? 'selected' : ''}}>{{$sample->reference}} - {{$sample->description}}</option>
                        @endforeach
                    </select>
                    {{--<input type="text" class="form-control" name="sample_article_id" value="{{@$order->sample_article_id}}">--}}
                </div>
            </div>

            <div class="row" id="sampleArticleDetails">
                @if(@$order->client_identifier)
                @include('orders.production.sampleArticleInUseTable')
                @endif
            </div>

            <h3>Quantidades a produzir:</h3>
            <table class="table table-striped thead-dark">
                <thead>
                <tr>
                    <th></th>
                    <th><input type="text" class="form-control" id="cor1" name="cor1" placeholder="Cor #1" value="{{@$order->cor1}}" readonly></th>
                    <th><input type="text" class="form-control" id="cor2" name="cor2" placeholder="Cor #2" value="{{@$order->cor2}}" readonly></th>
                    <th><input type="text" class="form-control" id="cor3" name="cor3" placeholder="Cor #3" value="{{@$order->cor3}}" readonly></th>
                    <th><input type="text" class="form-control" id="cor4" name="cor4" placeholder="Cor #4" value="{{@$order->cor4}}" readonly></th>
                </tr>
                </thead>
                <tbody>

                @for($i = 1; $i < 5; $i++)
                    @php $tamanho1 = "tamanho1".$i; $tamanho2 = "tamanho2".$i; $tamanho3 = "tamanho3".$i; $tamanho4 = "tamanho4".$i; $tamanho = "tamanho".$i; @endphp
                    <tr>
                        <td data-col1="Tamanho">
                            <input class="form-control" type="text" name="tamanho{{$i}}" id="tamanho{{$i}}" value="{{@$order->$tamanho}}" placeholder="T{{$i}}" readonly>
                        </td>
                        <td data-col2="Cor1">

                            <input type="number" name="tamanho1{{$i}}" id="tamanho1{{$i}}" class="sizes form-control" value="{{@$order->$tamanho1 ? @$order->$tamanho1 : 0}}">
                        </td>
                        <td data-col3="Cor2">
                            <input type="number" name="tamanho2{{$i}}" id="tamanho2{{$i}}" class="sizes form-control" value="{{@$order->$tamanho2 ? @$order->$tamanho2 : 0}}">
                        </td>
                        <td data-col4="Cor3">
                            <input type="number" name="tamanho3{{$i}}" id="tamanho3{{$i}}" class="sizes form-control" value="{{@$order->$tamanho3 ? @$order->$tamanho3 : 0}}">
                        </td>
                        <td data-col5="Cor4">
                            <input type="number" name="tamanho4{{$i}}" id="tamanho4{{$i}}" class="sizes form-control" value="{{@$order->$tamanho4 ? @$order->$tamanho4 : 0}}">
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
                    {{--<tr>--}}
                        {{--<td data-col1="">--}}
                            {{--Em Falta--}}
                        {{--</td>--}}
                        {{--<td data-col2="Em Falta da Cor1" id="falta1">--}}
                        {{--</td>--}}
                        {{--<td data-col3="Em Falta da Cor2" id="falta2">--}}
                        {{--</td>--}}
                        {{--<td data-col4="Em Falta da Cor3" id="falta3">--}}
                        {{--</td>--}}
                        {{--<td data-col5="Em Falta da Cor4" id="falta4">--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                </tbody>
            </table>
            <input type="hidden" name="filesToDelete" id="filesToDelete">
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

        $( document ).ready( function () {

            updateSizes ();
            updateAmounts ();

        });

        //remove files attached
        $(".fa-window-close").click ( function () {
            $("#filesToDelete").val($("#filesToDelete").val()+$(this).data("remove")+',');
            $( this ).parent().css('display', 'none');
        });

        $("#sampleArticleChange").change( function() {
            updateSizes();
            $("#sampleArticleDetails").html('<div class="col-md-3"></div><div class="col-md-6">\n' +
                '                    <label>A Amostra foi alterada. Por favor clique na imagem da amostra para consultar os detalhes caso necessário.</label>\n' +
                '                </div>');
        } );

        $(".sizes").keyup( updateAmounts );

        function updateSizes () {
            let id = $("#sampleArticleChange").val();
            $.ajax({
                url: "/orders/getSampleArticleId/"+id,
                success: function(result){
                    console.log(result);
                    $("#tamanho1").val(result['tamanho1']);
                    $("#tamanho2").val(result['tamanho2']);
                    $("#tamanho3").val(result['tamanho3']);
                    $("#tamanho4").val(result['tamanho4']);
                    $("#cor1").val(result['cor1']);
                    $("#cor2").val(result['cor2']);
                    $("#cor3").val(result['cor3']);
                    $("#cor4").val(result['cor4']);
                }
            });

            //Display image
            let src_img = $("#sampleArticleChange").find(':selected').data('src');
            let sampleArticleId = $("#sampleArticleChange").val();
            $("#image_sample").html('');
            $("#image_sample_desc").html('');
            if(src_img !== undefined){
                $("#image_sample").append('<img style="width:100px;cursor:pointer;border: 2px solid #797979;" src="../../storage/'+src_img+'" ' +
                    'onclick="window.open(\'../../samples/edit/'+sampleArticleId+'\',\'windowName\',\'height=600,width=800\');">');
                $("#image_sample_desc").append('Por favor clique na imagem da amostra para ver os detalhes.');
            }
        }

        function updateAmounts () {
            $("#pedido1").text(parseInt($("#tamanho11").val())+parseInt($("#tamanho12").val())+parseInt($("#tamanho13").val())+parseInt($("#tamanho14").val()));
            $("#pedido2").text(parseInt($("#tamanho21").val())+parseInt($("#tamanho22").val())+parseInt($("#tamanho23").val())+parseInt($("#tamanho24").val()));
            $("#pedido3").text(parseInt($("#tamanho31").val())+parseInt($("#tamanho32").val())+parseInt($("#tamanho33").val())+parseInt($("#tamanho34").val()));
            $("#pedido4").text(parseInt($("#tamanho41").val())+parseInt($("#tamanho42").val())+parseInt($("#tamanho43").val())+parseInt($("#tamanho44").val()));
        }

        //Behaviour of the changed status on desktop
        $(".btn-blue-grey").click( function() {
            let id = $( this ).data('id');
            //alert(id);
            $(".btn-blue-grey").removeClass('btn-highlighted');
            $(".btn-blue-grey").addClass('btn-circle-2');
            for(let i = 1; i <= id; i++) {
                $( "#step-"+i ).removeClass('btn-circle-2');
                $( "#step-"+i ).addClass('btn-highlighted');
            }
        });

        $(document).ready(function () {
            $("#sendOrder").submit( function() {
                let width = $( "body" ).width();
                if(width >= 752) {
                    //armazenar valor de desktop
                    let status1 = $(".btn-highlighted").length;
                    $("#status_id").val(status1);
                }
                else {
                    //armazenar valor de mobile
                    let status2 = $("#viaSelect").val();
                    $("#status_id").val(status2);
                }
            });
        });


    </script>
@endsection