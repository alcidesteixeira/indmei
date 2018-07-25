@extends('layouts.app')

@section('content')

    <style>
        .table td, .table th {
            padding: 0;
        }
        .form-group {
            margin-bottom: 5px;
        }
        .form-control {
            padding: 1px .75rem;
            line-height: 1;
        }
        .value-added {
            text-align: right;
        }
    </style>

    <div class="container">
        <h2>Atualizar Quantidade Produzida</h2><br/>

            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="Description">Identificador INDMEI:</label>
                    <span style="font-weight: bold;">{{$order->sampleArticle->reference}}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <label for="Description">Imagem Amostra:</label>
                    <img src="../../../storage/{{$order->sampleArticle->image_url}}" style="max-width: 200px;">
                </div>
                <div class="form-group col-md-9">
                    <table class="table table-striped thead-dark">
                        <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" class="form-control" value="{{$order->cor1}}" readonly></th>
                            <th><input type="text" class="form-control" value="{{$order->cor2}}" readonly></th>
                            <th><input type="text" class="form-control" value="{{$order->cor3}}" readonly></th>
                            <th><input type="text" class="form-control" value="{{$order->cor4}}" readonly></th>
                        </tr>
                        </thead>
                        <tbody>

                        @for($i = 1; $i < 5; $i++)
                            @php $tamanho1 = "tamanho1".$i; $tamanho2 = "tamanho2".$i; $tamanho3 = "tamanho3".$i; $tamanho4 = "tamanho4".$i; @endphp
                            @php $tamanho = "tamanho".$i; @endphp
                            <tr>
                                <td data-col1="Tamanho">
                                    <span type="text">{{$order->sampleArticle->$tamanho}}</span>
                                </td>
                                <td data-col2="Cor1">

                                    <input type="text" id="tamanho1{{$i}}" class="sizes form-control" value="{{$order->$tamanho1}}" readonly>
                                </td>
                                <td data-col3="Cor2">
                                    <input type="text" id="tamanho2{{$i}}" class="sizes form-control" value="{{$order->$tamanho2}}" readonly>
                                </td>
                                <td data-col4="Cor3">
                                    <input type="text" id="tamanho3{{$i}}" class="sizes form-control" value="{{$order->$tamanho3}}" readonly>
                                </td>
                                <td data-col5="Cor4">
                                    <input type="text" id="tamanho4{{$i}}" class="sizes form-control" value="{{$order->$tamanho4}}" readonly>
                                </td>
                            </tr>
                        @endfor
                        <tr>
                            <td data-col1="">
                                Pedido
                            </td>
                            <td data-col2="Pedido da Cor1" id="pedido1">
                                {{$order->tamanho11 + $order->tamanho12 +$order->tamanho13 +$order->tamanho14}}
                            </td>
                            <td data-col3="Pedido da Cor2" id="pedido2">
                                {{$order->tamanho21 + $order->tamanho22 +$order->tamanho23 +$order->tamanho24}}
                            </td>
                            <td data-col4="Pedido da Cor3" id="pedido3">
                                {{$order->tamanho31 + $order->tamanho32 +$order->tamanho33 +$order->tamanho34}}
                            </td>
                            <td data-col5="Pedido da Cor4" id="pedido4">
                                {{$order->tamanho41 + $order->tamanho42 +$order->tamanho43 +$order->tamanho44}}
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
                </div>
            </div>


            <table class="table table-striped thead-dark">
                <thead>
                <tr>
                    <th>Guiafios</th>
                    <th>Step</th>
                    <th>Gramas</th>
                    <th>Referência do Fio</th>
                    <th>Cor #1</th>
                    <th>Cor #2</th>
                    <th>Cor #3</th>
                    <th>Cor #4</th>
                </tr>
                </thead>
                <tbody>

                @for($i = 1; $i < sizeof($steps); $i++)
                    <tr>
                        <td data-col1="Guiafios">
                                @foreach($guiafios as $guia)
                                    @if($guia->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->guiafios_id)
                                        <span>{{$guia->description}}</span>
                                    @endif
                                @endforeach
                        </td>
                        <td data-col2="Step">
                            @foreach($steps as $step)
                                @if($step->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->step_id)
                                    <span>{{$step->step}}</span>
                                @endif
                            @endforeach
                        </td>
                        <td data-col3="Gramas">
                            <span>{{$order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->grams}}</span>
                        </td>
                        <td data-col4="Refrência do Fio">
                            @foreach($warehouseProducts as $product)
                                @if($product->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouse_product_id)
                                    <span>{{$product->reference}}</span>
                                @endif
                            @endforeach
                        </td>
                        <td data-col5="Cor #1">
                            @if(@$order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct)
                                @foreach($order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct->warehouseProductSpecs()->get() as $wireSpecs)
                                    @if($wireSpecs->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->wireColors()->get()->values()->get(0)->warehouse_product_spec_id)
                                        <span>{{$wireSpecs->color}}</span>
                                    @endif
                                @endforeach
                            @else
                                <span></span>
                            @endif
                        </td>
                        <td data-col6="Cor #2">
                            @if(@$order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct)
                                @foreach($order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct->warehouseProductSpecs()->get() as $wireSpecs)
                                    @if($wireSpecs->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->wireColors()->get()->values()->get(1)->warehouse_product_spec_id)
                                        <span>{{$wireSpecs->color}}</span>
                                    @endif
                                @endforeach
                            @else
                                <span></span>
                            @endif
                        </td>
                        <td data-col7="Cor #3">
                            @if(@$order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct)
                                @foreach($order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct->warehouseProductSpecs()->get() as $wireSpecs)
                                    @if($wireSpecs->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->wireColors()->get()->values()->get(2)->warehouse_product_spec_id)
                                        <span>{{$wireSpecs->color}}</span>
                                    @endif
                                @endforeach
                            @else
                                <span></span>
                            @endif
                        </td>
                        <td data-col8="Cor #4">
                            @if(@$order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct)
                                @foreach($order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct->warehouseProductSpecs()->get() as $wireSpecs)
                                    @if($wireSpecs->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->wireColors()->get()->values()->get(3)->warehouse_product_spec_id)
                                        <span>{{$wireSpecs->color}}</span>
                                    @endif
                                @endforeach
                            @else
                                <span></span>
                            @endif
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <div class="row">
            <table>
                <tr>
                    @for($i = 1; $i<=4; $i++)
                        @php($tamanho = 'tamanho'.$i) @php($pe = 'pe'.$i) @php($perna = 'perna'.$i)
                        @php($punho = 'punho'.$i) @php($malha = 'malha'.$i)@php($maq = 'maq'.$i)
                        @php($forma = 'forma'.$i)
                        <td style="border: 2px solid darkgray; text-align: center;">
                            <div class="form-group" style="margin: auto;">
                                <label>T{{$i}}: {{$order->sampleArticle->$tamanho}}</label>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="form-group col-md-5">
                                        <label for="pe">Pé:</label>
                                        <input type="text" class="form-control" value="{{$order->sampleArticle->$pe}}" required readonly >
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="Perna">Perna:</label>
                                        <input type="text" class="form-control" value="{{$order->sampleArticle->$perna}}" required readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="form-group col-md-5">
                                        <label for="Punho">Punho:</label>
                                        <input type="text" class="form-control" value="{{$order->sampleArticle->$punho}}" required readonly>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="Malha">Malha:</label>
                                        <input type="text" class="form-control" value="{{$order->sampleArticle->$malha}}" required readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="form-group col-md-5">
                                        <label for="Maq">Maq:</label>
                                        <input type="text" class="form-control" value="{{$order->sampleArticle->$maq}}" required readonly>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="Forma">Forma:</label>
                                        <input type="text" class="form-control" value="{{$order->sampleArticle->$forma}}" required readonly>
                                    </div>
                                </div>
                            </div>
                        </td>
                    @endfor
                </tr>
            </table>
        </div>


        <form method="post" action="{{url('order/production/update/'.$order->id)}}" enctype="multipart/form-data">
            @csrf
            <div class="row">

                @for($i = 1; $i <=4; $i++)
                @php($tamanho1 = 'tamanho1'.$i) @php($tamanho2 = 'tamanho2'.$i) @php($tamanho3 = 'tamanho3'.$i) @php($tamanho4 = 'tamanho4'.$i)

                <table class="table table-striped thead-dark table-bordered col-sm-3" style="border:2px solid #dee2e6; text-align:center">
                    <thead>
                        <tr>
                            <th id="tam1{{$i}}">{{round($order->$tamanho1 * 2 + ($order->$tamanho1 * 2 * 0.03))}}</th>
                            <th id="tam2{{$i}}">{{round($order->$tamanho2 * 2 + ($order->$tamanho2 * 2 * 0.03))}}</th>
                            <th id="tam3{{$i}}">{{round($order->$tamanho3 * 2 + ($order->$tamanho3 * 2 * 0.03))}}</th>
                            <th id="tam4{{$i}}">{{round($order->$tamanho4 * 2 + ($order->$tamanho4 * 2 * 0.03))}}</th>
                        </tr>
                        <tr>
                            <th>{{$order->cor1}}</th>
                            <th>{{$order->cor2}}</th>
                            <th>{{$order->cor3}}</th>
                            <th>{{$order->cor4}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prod_days as $key=>$day)

                        <tr class="toSubtract">
                            <td class=""><span style="position:absolute;left:4px; font-size:8px;">{{$key}}</span>
                                         <input type="number" data-table="{{$i}}" value="@php($valor = 'val'.$i.'1'){{array_key_exists('val'.$i.'1', $day) ? $day[$valor]: '0'}}" class="value-added tabela{{$i}} cor{{$i}}1" style="width:100%;background-color: #e9ecef" readonly></td>
                            <td class=""><input type="number" data-table="{{$i}}" value="@php($valor = 'val'.$i.'2'){{array_key_exists('val'.$i.'2', $day) ? $day[$valor]: '0'}}" class="value-added tabela{{$i}} cor{{$i}}2" style="width:100%;background-color: #e9ecef" readonly></td>
                            <td class=""><input type="number" data-table="{{$i}}" value="@php($valor = 'val'.$i.'3'){{array_key_exists('val'.$i.'3', $day) ? $day[$valor]: '0'}}" class="value-added tabela{{$i}} cor{{$i}}3" style="width:100%;background-color: #e9ecef" readonly></td>
                            <td class=""><input type="number" data-table="{{$i}}" value="@php($valor = 'val'.$i.'4'){{array_key_exists('val'.$i.'4', $day) ? $day[$valor]: '0'}}" class="value-added tabela{{$i}} cor{{$i}}4" style="width:100%;background-color: #e9ecef" readonly></td>
                        </tr>
                        @endforeach
                        <tr class="toSubtract">
                            <td class=""><span style="position:absolute;left:4px; font-size:8px;">{{date("Y-m-d")}}</span>
                                         <input type="number" data-table="{{$i}}" value="0" name="cor{{$i}}1" class="value-added tabela{{$i}} cor{{$i}}1" style="width:100%"></td>
                            <td class=""><input type="number" data-table="{{$i}}" value="0" name="cor{{$i}}2" class="value-added tabela{{$i}} cor{{$i}}2" style="width:100%"></td>
                            <td class=""><input type="number" data-table="{{$i}}" value="0" name="cor{{$i}}3" class="value-added tabela{{$i}} cor{{$i}}3" style="width:100%"></td>
                            <td class=""><input type="number" data-table="{{$i}}" value="0" name="cor{{$i}}4" class="value-added tabela{{$i}} cor{{$i}}4" style="width:100%"></td>
                        </tr>
                        <tr class="missing">
                            <td class="missing1{{$i}}">
                                                    {{round($order->$tamanho1 * 2 + ($order->$tamanho1 * 2 * 0.03))}}</td>
                            <td class="missing2{{$i}}">{{round($order->$tamanho2 * 2 + ($order->$tamanho2 * 2 * 0.03))}}</td>
                            <td class="missing3{{$i}}">{{round($order->$tamanho3 * 2 + ($order->$tamanho3 * 2 * 0.03))}}</td>
                            <td class="missing4{{$i}}">{{round($order->$tamanho4 * 2 + ($order->$tamanho4 * 2 * 0.03))}}</td>
                        </tr>
                    </tbody>
                </table>
                @endfor
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6" style="margin-top:60px;margin-bottom:40px;">
                    <button type="submit" class="btn btn-success">Atualizar</button>
                    <button type="button" onclick="window.history.back();" class="btn btn-info">Voltar</button>
                </div>
            </div>

        </form>
    </div>

    <script>

        updateValues ();
        updateEmFalta ();
        $(".value-added").keyup( updateValues );

        function updateValues () {
            {{--$( this ).parent().parent().parent().append("<tr class='toSubtract'>" +--}}
                {{--"<td class=''><input type='number' data-table='{{$i}}' name='cor{{$i}}1' class='value-added tabela{{$i}} cor{{$i}}1' style='width:100%'></td>" +--}}
                {{--"<td class=''><input type='number' data-table='{{$i}}' name='cor{{$i}}2' class='value-added tabela{{$i}} cor{{$i}}2' style='width:100%'></td>" +--}}
                {{--"<td class=''><input type='number' data-table='{{$i}}' name='cor{{$i}}3' class='value-added tabela{{$i}} cor{{$i}}3' style='width:100%'></td>" +--}}
                {{--"<td class=''><input type='number' data-table='{{$i}}' name='cor{{$i}}4' class='value-added tabela{{$i}} cor{{$i}}4' style='width:100%'></td>" +--}}
                {{--"</tr>");--}}
            //Count Values to Subtract to Totals for each table T1 T2 T3 T4
            //Retorna um array com todos os valores somados que se terão de subtrair ao total
            let arrayToSubtract = [];
            for(let i = 1; i <= 4; i++) {
                let totalCor = 0;
                for(let j = 1; j <= 4; j++) {
                    let length = $('.cor'+i+j).length;
                    let increment = 0;
                    arrayToSubtract['cor'+i+j] = '';
                    $('.cor'+i+j).each(function () {
                        increment ++;
                        arrayToSubtract['cor'+i+j] += $(this).val();
                        if(increment !== length) {arrayToSubtract['cor'+i+j] += ',';}
                    });
                    //Com os valores a subtrair numa só variável, subtrair neste momento:
                    let subtracts = JSON.parse("[" + arrayToSubtract['cor'+i+j] + "]");
                    arrayToSubtract['cor'+i+j] = subtracts.reduce((a, b) => a + b, 0);
                    $(".missing"+j+i).text($("#tam"+j+i).text() - arrayToSubtract['cor'+i+j]);
                }
            }
            //END - Retorna um array com todos os valores somados que se terão de subtrair ao total - END
            //console.log(arrayToSubtract);

            // for(let i = 1; i <= 4; i++) {
            //     for(let j = 1; j <= 4; j++) {
            //         $("#missing1" + i).text($("#tam1" + i).text() - arrayToSubtract['cor'+j+i]);
            //         $("#missing2" + i).text($("#tam2" + i).text() - arrayToSubtract['cor'+j+i]);
            //         $("#missing3" + i).text($("#tam3" + i).text() - arrayToSubtract['cor'+j+i]);
            //         $("#missing4" + i).text($("#tam4" + i).text() - arrayToSubtract['cor'+j+i]);
            //     }
            // }
        }

        function updateEmFalta () {
            let arrayToSubtract = [];
            for(let i = 1; i <= 4; i++) {
                let ar = 0;
                for(let j = 1; j <= 4; j++) {
                    $('.cor' + j + i).each(function () {
                        //console.log(parseInt($(this).val()));
                        ar += parseInt($(this).val());
                    });
                    arrayToSubtract['a'+i] = ar;
                    //em falta 1ª tabela
                    //console.log($("#pedido" + i).text());
                    //console.log(arrayToSubtract);
                    //$("#falta" + i).text($("#pedido" + i).text() - (arrayToSubtract / 2).toFixed(0));
                }
                //console.log(arrayToSubtract);
                $("#falta" + i).text($("#pedido" + i).text() - (arrayToSubtract['a'+i] / 2).toFixed(0));
            }
            //console.log(arrayToSubtract);
        }

    </script>

@endsection