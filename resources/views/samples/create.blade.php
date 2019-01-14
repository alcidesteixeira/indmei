@extends('layouts.app')

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

@section('content')
    <div class="container">
        <div class="row">
            <h2>{{@$sampleArticle->reference && !@$isDuplicate ? 'Atualizar Amostra' : (@$isDuplicate ? 'Duplicar Amostra' : 'Criar Nova Amostra')}}</h2>
            @if(!@$sampleArticle->reference || @$isDuplicate)
            <span style="margin: 10px 0 0 30px;">
                Criar amostra a partir de uma existente:
                <select name="sampleBase" id="sampleArticleBase" class="form-control">
                    <option value="0">-</option>
                    @foreach($sampleIdsAndDesc as $sample)
                    <option value="{{$sample->id}}" {{$sample->id == @$id ? 'selected' : ''}}>{{$sample->reference . ' - ' . $sample->description}}</option>
                    @endforeach
                </select>
            </span>
            @endif
        </div>
        <br/>
        <form method="post" action="{{@$sampleArticle->reference && !@$isDuplicate ? url('samples/update/'.$sampleArticle->id) : (@$isDuplicate ? url('samples/create') : url('samples/create'))}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-3">
                    <label for="Reference">Amostra INDMEI:</label>
                    <input type="text" class="form-control" name="reference" value="{{@$isDuplicate ? '' : @$sampleArticle->reference}}" required>
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
                    <img id="blah" style="width: 200px;margin-bottom: 20px;" src="../../storage/{{@$sampleArticle->image_url}}" />
                </div>
                <input type="hidden" value="{{@$sampleArticle->image_url}}" name="img_path_duplicated">
            </div>


            <div class="row" style="margin-bottom:20px;">
                <table>
                    <tr>
                        @for($i = 1; $i<=4; $i++)
                            @php($tamanho = 'tamanho'.$i) @php($pe = 'pe'.$i) @php($perna = 'perna'.$i)
                            @php($punho = 'punho'.$i) @php($malha = 'malha'.$i)@php($maq = 'maq'.$i)
                            @php($forma = 'forma'.$i)
                            <td style="border: 2px solid darkgray; text-align: center;">
                                <div class="form-group" style="margin: auto;">
                                    <label><input type="text" class="form-control" name="tamanho{{$i}}" value="{{@$sampleArticle->$tamanho}}"></label>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="form-group col-md-5">
                                            <label for="pe">Pé:</label>
                                            <input type="text" class="form-control" name="pe{{$i}}" value="{{@$sampleArticle->$pe}}">
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="Perna">Perna:</label>
                                            <input type="text" class="form-control" name="perna{{$i}}" value="{{@$sampleArticle->$perna}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="form-group col-md-5">
                                            <label for="Punho">Punho:</label>
                                            <input type="text" class="form-control" name="punho{{$i}}" value="{{@$sampleArticle->$punho}}">
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="Malha">Malha:</label>
                                            <input type="text" class="form-control" name="malha{{$i}}" value="{{@$sampleArticle->$malha}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="form-group col-md-5">
                                            <label for="Maq">Maq:</label>
                                            <input type="text" class="form-control" name="maq{{$i}}" value="{{@$sampleArticle->$maq}}">
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="Forma">Forma:</label>
                                            <input type="text" class="form-control" name="forma{{$i}}" value="{{@$sampleArticle->$forma}}">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        @endfor
                    </tr>
                </table>
            </div>

            <hr>
            <p>* Ao colocar o campo de <b>Guiafios</b> vazia, toda a linha correspondente ficará sem dados.</p>
            <table class="table table-striped thead-dark">
                <thead>
                <tr>
                    <th>Função</th>
                    <th>Guiafios*</th>
                    <th>Gramas</th>
                    <th>Referência INDMEI</th>
                    <th>Cor #1</th>
                    <th>Cor #2</th>
                    <th>Cor #3</th>
                    <th>Cor #4</th>
                </tr>
                </thead>
                <tbody>

                @for($i = 1; $i < sizeof($steps); $i++)
                    <tr id="theRow" style="@if(in_array($steps[$i-1]->step, ['G8', 'BR5', 'BR6', 'BR7', 'BR8'])) display: none @endif">
                        <td data-col1="Função">
                            <select size="1" data-row="{{$i}}" id="row-{{$i}}-guiafios" name="row-{{$i}}-guiafios" class="form-control">
                                @foreach($guiafios as $guia)
                                    <option value="{{$guia->id}}" {{@$sampleArticle && $guia->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->guiafios_id ? 'selected' : ''}}>
                                        {{$guia->description}}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td data-col2="Guiafios">
                            <select size="1" class="stepEmpty form-control" data-row="{{$i}}" id="row-{{$i}}-step" name="row-{{$i}}-step">
                                @foreach($steps as $step)
                                {{--Primeiro if acontece ao criar para listar todos os steps direitinhos--}}
                                {{--Segundo if é para no edit aparecer tudo o que está na BD guardado--}}
                                <option value="{{$step->id}}" {{$step->id == $i ? 'selected' : @$sampleArticle && $step->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->step_id ?
                                 'selected' : ''}}>
                                    {{$step->step}}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td data-col3="Gramas">
                            <input type="number" id="row-{{$i}}-grams"  class="form-control" name="row-{{$i}}-grams" style="max-width:100px"
                                   value="{{@$sampleArticle && $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->grams
                            && $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->step_id !== '18' ?
                            $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->grams : '0'}}">
                        </td>
                        <td data-col4="Refrência do Fio">
                            <select size="1" class="referenceChanged form-control" data-row="{{$i}}" id="row-{{$i}}-reference" name="row-{{$i}}-reference">
                                @foreach($warehouseProducts as $product)
                                <option value="{{@$product->id}}" {{@$sampleArticle && $product->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouse_product_id ? 'selected' : ''}}>
                                    {{@$product->reference}}
                                </option>
                                @endforeach
                                <option value="default" {{@$sampleArticle && $step->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->step_id ? 'selected' : ''}}>
                                </option>
                            </select>
                        </td>
                        <td data-col5="Cor #1">
                            <select size="1" id="row-{{$i}}-color1" name="row-{{$i}}-color1" class="form-control">
                                {{--Caso a cor esteja diferente de vazio--}}
                                @if(@$sampleArticle && $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouse_product_id !== 'default')
                                    {{--Fios da amostra, ou fios default--}}
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
                        <td data-col6="Cor #2">
                            <select size="1" id="row-{{$i}}-color2" name="row-{{$i}}-color2" class="form-control">
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
                        <td data-col7="Cor #3">
                            <select size="1" id="row-{{$i}}-color3" name="row-{{$i}}-color3" class="form-control">
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
                        <td data-col8="Cor #4">
                            <select size="1" id="row-{{$i}}-color4" name="row-{{$i}}-color4" class="form-control">
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
                    <button type="submit" onclick="beforeInput();" class="btn btn-success">{{@$sampleArticle->reference && !@$isDuplicate ? 'Atualizar' : (@$isDuplicate ? 'Duplicar' : 'Criar')}}</button>
                    <button type="button" onclick="window.history.back();" class="btn btn-info">Voltar</button>
                </div>
            </div>
        </form>
        <div class="loaderContainer" style="display:none">
            <div class="loader"></div>
        </div>

    </div>


    @if(!(@$sampleArticle->reference))

    @endif

    <script>

        //Duplicar oferta:
        $("#sampleArticleBase").change(function () {
            $(".loaderContainer").css('display', 'block');

            let baseId = $(this).val();
            window.location.replace('/samples/getForDuplicate/'+baseId);

            // $.ajax({
            //     url: "/samples/getForDuplicate/"+baseId,
            //     contentType: "application/json",
            //     type: "GET",
            //     success: function(result){
            //         console.log(result);
            //         $(".loaderContainer").css('display', 'none');
            //
            //     }
            // });

        });
        //Duplicar oferta end

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
                contentType: "application/json",
                type: "GET",
                success: function(result){
                    console.log(result);
                    $( "#row-"+rowSelected+"-color1, #row-"+rowSelected+"-color2, #row-"+rowSelected+"-color3, #row-"+rowSelected+"-color4").empty();
                    $.each(result, function (key, value) {
                        $( "#row-"+rowSelected+"-color1, #row-"+rowSelected+"-color2, #row-"+rowSelected+"-color3, #row-"+rowSelected+"-color4")
                            .append('<option value="'+key+'">'+value+'</option>');
                    });
                }
            });
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
                $("#row-"+rowSelected+"-grams").val("");
                $("#row-"+rowSelected+"-guiafios").val("9");
            }
            else {
                $("#row-"+rowSelected+"-reference, #row-"+rowSelected+"-color1, #row-"+rowSelected+"-color2, #row-"+rowSelected+"-color3, #row-"+rowSelected+"-color4").val("1");
                requestWiresToDB({!! @$warehouseFirstWireSpecs[0]->warehouse_product_id ? $warehouseFirstWireSpecs[0]->warehouse_product_id : 0 !!}, rowSelected);
                $("#row-"+rowSelected+"-grams").val("0");
                $("#row-"+rowSelected+"-guiafios").val("1");
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

    @if(@$sampleArticle->reference == null)
    <script>
        //Run function
        for(let i = 1; i <= $('table tr').length - 1; i++) {
            if($("#row-"+i+"-step").val() !== '18') {
                requestWiresToDB({!! @$warehouseFirstWireSpecs[0]->warehouse_product_id ? $warehouseFirstWireSpecs[0]->warehouse_product_id : 0 !!}, i);
            }
        }
    </script>
    @endif

@endsection