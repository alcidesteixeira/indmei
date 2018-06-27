@extends('layouts.app')

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

@section('content')
    <div class="container">
        <h2>{{@$sampleArticle->reference ? 'Atualizar Amostra de Artigo' : 'Criar Nova Amostra de Artigo'}}</h2><br/>
        <form method="post" action="{{@$sampleArticle->reference ? url('samples/update/'.$sampleArticle->id) : url('samples/create')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="Status">Status:</label>
                    {{--<input type="text" class="form-control" name="status_id" value="{{@$sampleArticle->sample_article_status_id}}" required>--}}
                    <select class="form-control" name="status_id">
                        @foreach($statuses as $status)
                        <option value="{{$status->id}}" {{$status->id == @$sampleArticle->sample_article_status_id ? 'selected' : ''}}>{{$status->status}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-3">
                    <label for="Reference">Referência:</label>
                    <input type="text" class="form-control" name="reference" value="{{@$sampleArticle->reference}}" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="Description">Descrição:</label>
                    <input type="text" class="form-control" name="description" value="{{@$sampleArticle->description}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-3">
                    <label for="Image">Imagem:</label>
                    <input type="file" class="form-control-file" name="image_url" id="imgInp" {{@$sampleArticle->reference ? '' : 'required'}}>
                </div>
                <div class="col-md-3">
                    <img id="blah" style="width: 200px;" src="../../storage/{{@$sampleArticle->image_url}}" />
                </div>
            </div>


            <div class="container">
                <ul class="nav nav-pills">
                    <li class="active"><a data-toggle="pill" href="#menu1">T1</a></li>
                    <li><a data-toggle="pill" href="#menu2">T2</a></li>
                    <li><a data-toggle="pill" href="#menu3">T3</a></li>
                    <li><a data-toggle="pill" href="#menu4">T4</a></li>
                </ul>

                <div class="tab-content">
                    @for($i = 1; $i<=4; $i++)
                        @php($tamanho = 'tamanho'.$i) @php($pe = 'pe'.$i) @php($perna = 'perna'.$i)
                        @php($punho = 'punho'.$i) @php($malha = 'malha'.$i)@php($maq = 'maq'.$i)
                        @php($forma = 'forma'.$i)
                    <div id="menu{{$i}}" class="tab-pane fade {{$i == '1' ? 'in active show' : ''}}">
                        <h3>T{{$i}}:</h3><input type="text" class="form-control form-horizontal col-sm-3" name="tamanho{{$i}}" value="{{@$sampleArticle->$tamanho}}" required>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="form-group col-md-3">
                                <label for="pe">Pé:</label>
                                <input type="text" class="form-control" name="pe{{$i}}" value="{{@$sampleArticle->$pe}}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Perna">Perna:</label>
                                <input type="text" class="form-control" name="perna{{$i}}" value="{{@$sampleArticle->$perna}}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="form-group col-md-3">
                                <label for="Punho">Punho:</label>
                                <input type="text" class="form-control" name="punho{{$i}}" value="{{@$sampleArticle->$punho}}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Malha">Malha:</label>
                                <input type="text" class="form-control" name="malha{{$i}}" value="{{@$sampleArticle->$malha}}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="form-group col-md-3">
                                <label for="Maq">Maq:</label>
                                <input type="text" class="form-control" name="maq{{$i}}" value="{{@$sampleArticle->$maq}}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Forma">Forma:</label>
                                <input type="text" class="form-control" name="forma{{$i}}" value="{{@$sampleArticle->$forma}}" required>
                            </div>
                        </div>
                    </div>
                        @endfor
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
                        <td>
                            <select size="1" data-row="{{$i}}" id="row-{{$i}}-guiafio" name="row-{{$i}}-guiafio">
                                @foreach($guiafios as $guia)
                                    <option value="{{$guia->id}}" {{@$sampleArticle && $guia->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->guiafios_id ? 'selected' : ''}}>
                                        {{$guia->description}}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select size="1" class="stepEmpty" data-row="{{$i}}" id="row-{{$i}}-step" name="row-{{$i}}-step">
                                @foreach($steps as $step)
                                <option value="{{$step->id}}" {{@$sampleArticle && $step->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->step_id ? 'selected' : ''}}>
                                    {{$step->step}}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" id="row-{{$i}}-grams" name="row-{{$i}}-grams" value="{{@$sampleArticle && $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->grams
                            && $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->step_id !== '18' ?
                            $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->grams : ''}}">
                        </td>
                        <td>
                            <select size="1" class="referenceChanged" data-row="{{$i}}" id="row-{{$i}}-reference" name="row-{{$i}}-reference">
                                @foreach($warehouseProducts as $product)
                                <option value="{{@$product->id}}" {{@$sampleArticle && $product->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouse_product_id ? 'selected' : ''}}>
                                    {{@$product->reference}}
                                </option>
                                @endforeach
                                <option value="default" {{@$sampleArticle && $step->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->step_id ? 'selected' : ''}}></option>
                            </select>
                        </td>
                        <td>
                            <select size="1" id="row-{{$i}}-color1" name="row-{{$i}}-color1">
                                @if(@$sampleArticle && $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouse_product_id !== 'default')
                                    @if(@$sampleArticle)
                                        @foreach(@$sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct->warehouseProductSpecs()->get() as $wireSpecs)
                                        <option value="{{$wireSpecs->id}}"
                                                {{$wireSpecs->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->wireColors()->get()->values()->get(0)->warehouse_product_spec_id
                                                ? 'selected' : ''}}>
                                            {{$wireSpecs->color}}
                                        </option>
                                        @endforeach
                                    @else
                                        @foreach(@$warehouseFirstWireSpecs as $firstWireSpecs)
                                            <option value="{{$firstWireSpecs->id}}">
                                                {{$firstWireSpecs->color}}
                                            </option>
                                        @endforeach
                                    @endif
                                    @else
                                    <option value="default" {{@$sampleArticle && $step->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->step_id ? 'selected' : ''}}></option>
                                @endif
                            </select>
                        </td>
                        <td>
                            <select size="1" id="row-{{$i}}-color2" name="row-{{$i}}-color2">
                                @if(@$sampleArticle && $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouse_product_id !== 'default')
                                    @if(@$sampleArticle)
                                        @foreach(@$sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct->warehouseProductSpecs()->get() as $wireSpecs)
                                            <option value="{{$wireSpecs->id}}"
                                            {{$wireSpecs->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->wireColors()->get()->values()->get(1)->warehouse_product_spec_id
                                            ? 'selected' : ''}}>
                                                {{$wireSpecs->color}}
                                            </option>
                                        @endforeach
                                    @else
                                        @foreach(@$warehouseFirstWireSpecs as $firstWireSpecs)
                                            <option value="{{$firstWireSpecs->id}}">
                                                {{$firstWireSpecs->color}}
                                            </option>
                                        @endforeach
                                    @endif
                                @else
                                    <option value="default" {{@$sampleArticle && $step->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->step_id ? 'selected' : ''}}></option>
                                @endif
                            </select></td>
                        <td>
                            <select size="1" id="row-{{$i}}-color3" name="row-{{$i}}-color3">
                                @if(@$sampleArticle && $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouse_product_id !== 'default')
                                    @if(@$sampleArticle)
                                        @foreach(@$sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct->warehouseProductSpecs()->get() as $wireSpecs)
                                            <option value="{{$wireSpecs->id}}"
                                            {{$wireSpecs->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->wireColors()->get()->values()->get(2)->warehouse_product_spec_id
                                            ? 'selected' : ''}}>
                                                {{$wireSpecs->color}}
                                            </option>
                                        @endforeach
                                    @else
                                        @foreach(@$warehouseFirstWireSpecs as $firstWireSpecs)
                                            <option value="{{$firstWireSpecs->id}}">
                                                {{$firstWireSpecs->color}}
                                            </option>
                                        @endforeach
                                    @endif
                                @else
                                    <option value="default" {{@$sampleArticle && $step->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->step_id ? 'selected' : ''}}></option>
                                @endif
                            </select>
                        </td>
                        <td>
                            <select size="1" id="row-{{$i}}-color4" name="row-{{$i}}-color4">
                                @if(@$sampleArticle && $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouse_product_id !== 'default')
                                    @if(@$sampleArticle)
                                        @foreach(@$sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct->warehouseProductSpecs()->get() as $wireSpecs)
                                            <option value="{{$wireSpecs->id}}"
                                            {{$wireSpecs->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->wireColors()->get()->values()->get(3)->warehouse_product_spec_id
                                            ? 'selected' : ''}}>
                                                {{$wireSpecs->color}}
                                            </option>
                                        @endforeach
                                    @else
                                        @foreach(@$warehouseFirstWireSpecs as $firstWireSpecs)
                                            <option value="{{$firstWireSpecs->id}}">
                                                {{$firstWireSpecs->color}}
                                            </option>
                                        @endforeach
                                    @endif
                                @else
                                    <option value="default" {{@$sampleArticle && $step->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->step_id ? 'selected' : ''}}></option>
                                @endif
                            </select>
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>


            <div class="row">
                <div class="col-md-3"></div>
                <div class="submit-buttons form-group col-md-6" style="margin-top:60px">
                    <button type="submit" onclick="beforeInput();" class="btn btn-success">{{@$sampleArticle->reference ? 'Atualizar' : 'Criar'}}</button>
                    <button type="button" onclick="window.history.back();" class="btn btn-info">Voltar</button>
                </div>
            </div>
        </form>
    </div>


    <script>
        //Filter and order table
        // $('table').DataTable({
        //     "ordering": false,
        //     "pageLength": 25,
        //     "language": {
        //         "lengthMenu": "Apresentar _MENU_ resultados por página",
        //         "zeroRecords": "Nenhum resultado encontrado.",
        //         "info": "Página _PAGE_ de _PAGES_",
        //         "infoEmpty": "Sem resultados disponíveis",
        //         "infoFiltered": "(Filtrado de _MAX_ resultados totais)",
        //         "paginate": {
        //             "first":      "Primeira",
        //             "last":       "Última",
        //             "next":       "Seguinte",
        //             "previous":   "Anterior"
        //         },
        //         "loadingRecords": "A pesquisar...",
        //         "processing":     "A processar...",
        //         "search":         "Pesquisar:",
        //     }
        // });

        function beforeInput ()
        {
            let rowCount = $('table tr').length - 1;
            let colorsCount = $('table tr th').length - 3;
            $("form").append('<input type="hidden" name="rowCount" value="'+rowCount+'">' +
                '<input type="hidden" name="colorsCount" value="'+colorsCount+'">');
        }

        //Function to request wire colors when you select one specific wire for a specific row
        function requestWiresToDB (wireSelectedId, rowSelected)
        {
            $.ajax({
                url: "/samples/updatewirespecs/"+wireSelectedId,
                success: function(result){
                    $( "#row-"+rowSelected+"-color1, #row-"+rowSelected+"-color2, #row-"+rowSelected+"-color3, #row-"+rowSelected+"-color4").empty();
                    $.each(result, function (key, value) {
                        $( "#row-"+rowSelected+"-color1, #row-"+rowSelected+"-color2, #row-"+rowSelected+"-color3, #row-"+rowSelected+"-color4")
                            .append('<option value="'+key+'">'+value+'</option>');
                    });
                }
            });
        }

        //Run function
        for(let i = 1; i <= $('table tr').length - 1; i++)
        {
            requestWiresToDB (1, i);
        }

        //Whenever user changes wire, queries database to output correct wire colors
        $(" .referenceChanged ").change( function () {
            let wireSelectedId = $( ' option:selected', this).val();
            let rowSelected = $( this ).data('row');
            requestWiresToDB(wireSelectedId, rowSelected);
        });

        //Whenever user choses empty step, empties every field of the row
        $(" .stepEmpty ").change( function () {
            let val = ($(this).val());
            let rowSelected = $( this ).data('row');
            if(val == '18') {
                $("#row-"+rowSelected+"-reference, #row-"+rowSelected+"-color1, #row-"+rowSelected+"-color2, #row-"+rowSelected+"-color3, #row-"+rowSelected+"-color4").val("default");
                $("#row-"+rowSelected+"-weight").val("");
            }
            else {
                $("#row-"+rowSelected+"-reference, #row-"+rowSelected+"-color1, #row-"+rowSelected+"-color2, #row-"+rowSelected+"-color3, #row-"+rowSelected+"-color4").val("1");
                requestWiresToDB(1, rowSelected);
            }
        });

        //Preview image before upload
        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function() {
            readURL(this);
            $("#blah").css('display', 'block');
        });
    </script>

@endsection