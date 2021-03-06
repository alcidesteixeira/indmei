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
                    <input style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;" type="text" class="form-control" name="reference" value="{{@$isDuplicate ? '' : @$sampleArticle->reference}}" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="Description">Descrição:</label>
                    <input style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;" type="text" class="form-control" name="description" value="{{@$sampleArticle->description}}" required>
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
                                    <label><input style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;" type="text" class="form-control" name="tamanho{{$i}}" value="{{@$sampleArticle->$tamanho}}"></label>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="form-group col-md-5">
                                            <label for="pe">Pé:</label>
                                            <input style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;" type="text" class="form-control" name="pe{{$i}}" value="{{@$sampleArticle->$pe}}">
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="Perna">Perna:</label>
                                            <input style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;" type="text" class="form-control" name="perna{{$i}}" value="{{@$sampleArticle->$perna}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="form-group col-md-5">
                                            <label for="Punho">Punho:</label>
                                            <input style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;" type="text" class="form-control" name="punho{{$i}}" value="{{@$sampleArticle->$punho}}">
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="Malha">Malha:</label>
                                            <input style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;" type="text" class="form-control" name="malha{{$i}}" value="{{@$sampleArticle->$malha}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="form-group col-md-5">
                                            <label for="Maq">Maq:</label>
                                            <input style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;" type="text" class="form-control" name="maq{{$i}}" value="{{@$sampleArticle->$maq}}">
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="Forma">Forma:</label>
                                            <input style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;" type="text" class="form-control" name="forma{{$i}}" value="{{@$sampleArticle->$forma}}">
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
                    <th>
                        <input type="text" class="form-control" name="cor1" style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;max-width:100px" value="{{@$sampleArticle->cor1 ?: 'Cor1'}}">
                    </th>
                    <th>
                        <input type="text" class="form-control" name="cor2" style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;max-width:100px" value="{{@$sampleArticle->cor2 ?: 'Cor2'}}">
                    </th>
                    <th>
                        <input type="text" class="form-control" name="cor3" style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;max-width:100px" value="{{@$sampleArticle->cor3 ?: 'Cor3'}}">
                    </th>
                    <th>
                        <input type="text" class="form-control" name="cor4" style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;max-width:100px" value="{{@$sampleArticle->cor4 ?: 'Cor4'}}">
                    </th>
                </tr>
                </thead>
                <tbody>

                @for($i = 1; $i < sizeof($steps); $i++)
                    <tr id="theRow" style="@if(in_array($steps[$i-1]->step, ['G8', 'BR5', 'BR6', 'BR7', 'BR8'])) display: none @endif">
                        <td data-col1="Função">
                            <input style="font-size: .9rem;line-height: 1.6;padding:0 12px !important;padding: 0 12px;" type="text" data-row="{{$i}}" id="row-{{$i}}-guiafios" name="row-{{$i}}-guiafios" class="form-control"
                                value="@if(@$sampleArticle && is_numeric($sample_guiafios_array[$i-1])){{$guiafios[$sample_guiafios_array[$i-1]]}}@elseif(@$sampleArticle && !is_numeric($sample_guiafios_array[$i-1])){{$sample_guiafios_array[$i-1]}}@endif">
                        </td>
                        <td data-col2="Guiafios">
                            <select size="1" class="stepEmpty form-control" data-row="{{$i}}" id="row-{{$i}}-step" name="row-{{$i}}-step">
                                @foreach($steps as $step)
                                {{--Primeiro if acontece ao criar para listar todos os steps direitinhos--}}
                                {{--Segundo if é para no edit aparecer tudo o que está na BD guardado--}}
                                <option value="{{$step->id}}"
                                        {{$step->id == $i ? 'selected' :
                                        @$sampleArticle &&
                                        $step->id == $sample_steps_array[$i-1] ?
                                 'selected' : ''}}>
                                    {{$step->step}}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td data-col3="Gramas">
                            <input style="font-size: .9rem;width: 80px;line-height: 1.6;padding:0 12px !important;padding: 0 12px;" type="number" id="row-{{$i}}-grams"  class="form-control sum_grams" name="row-{{$i}}-grams" style="max-width:100px"
                                   value="{{@$sampleArticle && $sample_grams_array[$i-1]
                            && $sample_steps_array[$i-1] !== '18' ?
                            $sample_grams_array[$i-1] : '0'}}">
                        </td>

                        <td data-col4="Refrência do Fio">
                            <select size="1" class="referenceChanged form-control" data-row="{{$i}}" id="row-{{$i}}-reference" name="row-{{$i}}-reference">
                                @foreach($warehouseProducts as $product)
                                <option value="{{@$product->id}}" {{@$sampleArticle && $product->id == $sample_wp_array[$i-1] ? 'selected' : ''}}>
                                    {{@$product->reference}}
                                </option>
                                @endforeach
                                <option value="default" {{@$sampleArticle && $step->id == $sample_steps_array[$i-1] ? 'selected' : ''}}>
                                </option>
                            </select>
                        </td>

                        <td data-col5="Cor #1">
                            <select size="1" id="row-{{$i}}-color1" name="row-{{$i}}-color1" class="form-control">
                                @if(@$sampleArticle && isset($sample_wp_specs_array[$i-1]) && $sample_wp_array[$i-1] !== 'default'))
                                    @if(@$sampleArticle)
                                        @foreach($warehouseProductSpecsArray[$sample_wp_array[$i-1]] as $key => $color)
                                            <option value="{{$key}}"
                                                {{$key == $sample_wp_specs_array[$i-1][0]
                                                ? 'selected' : ''}}>
                                                {{$color}}
                                            </option>
                                        @endforeach
                                        <option value="default"
                                            {{(!isset($sample_wp_specs_array[$i-1]) || $sample_wp_specs_array[$i-1][0] == 'default') ?
                                            'selected' : ''}}></option>
                                    @else
                                        @foreach(@$warehouseFirstWireSpecs as $firstWireSpecs)
                                            <option value="{{$firstWireSpecs->id}}">
                                                {{$firstWireSpecs->color}}
                                            </option>
                                        @endforeach
                                    @endif
                                @else
                                    <option value="default" {{@$sampleArticle && $step->id == $sample_steps_array[$i-1] ? 'selected' : ''}}></option>
                                @endif
                            </select>
                        </td>

                        <td data-col5="Cor #2">
                            <select size="1" id="row-{{$i}}-color2" name="row-{{$i}}-color2" class="form-control">
                                @if(@$sampleArticle && isset($sample_wp_specs_array[$i-1]) && $sample_wp_array[$i-1] !== 'default'))
                                @if(@$sampleArticle)
                                    @foreach($warehouseProductSpecsArray[$sample_wp_array[$i-1]] as $key => $color)
                                        <option value="{{$key}}"
                                                {{$key == $sample_wp_specs_array[$i-1][1]
                                                ? 'selected' : ''}}>
                                            {{$color}}
                                        </option>
                                    @endforeach
                                    <option value="default"
                                            {{(!isset($sample_wp_specs_array[$i-1]) || $sample_wp_specs_array[$i-1][1] == 'default') ?
                                            'selected' : ''}}></option>
                                @else
                                    @foreach(@$warehouseFirstWireSpecs as $firstWireSpecs)
                                        <option value="{{$firstWireSpecs->id}}">
                                            {{$firstWireSpecs->color}}
                                        </option>
                                    @endforeach
                                @endif
                                @else
                                    <option value="default" {{@$sampleArticle && $step->id == $sample_steps_array[$i-1] ? 'selected' : ''}}></option>
                                @endif
                            </select>
                        </td>

                        <td data-col5="Cor #3">
                            <select size="1" id="row-{{$i}}-color3" name="row-{{$i}}-color3" class="form-control">
                                @if(@$sampleArticle && isset($sample_wp_specs_array[$i-1]) && $sample_wp_array[$i-1] !== 'default'))
                                @if(@$sampleArticle)
                                    @foreach($warehouseProductSpecsArray[$sample_wp_array[$i-1]] as $key => $color)
                                        <option value="{{$key}}"
                                                {{$key == $sample_wp_specs_array[$i-1][2]
                                                ? 'selected' : ''}}>
                                            {{$color}}
                                        </option>
                                    @endforeach
                                    <option value="default"
                                            {{(!isset($sample_wp_specs_array[$i-1]) || $sample_wp_specs_array[$i-1][2] == 'default') ?
                                            'selected' : ''}}></option>
                                @else
                                    @foreach(@$warehouseFirstWireSpecs as $firstWireSpecs)
                                        <option value="{{$firstWireSpecs->id}}">
                                            {{$firstWireSpecs->color}}
                                        </option>
                                    @endforeach
                                @endif
                                @else
                                    <option value="default" {{@$sampleArticle && $step->id == $sample_steps_array[$i-1] ? 'selected' : ''}}></option>
                                @endif
                            </select>
                        </td>

                        <td data-col5="Cor #4">
                            <select size="1" id="row-{{$i}}-color4" name="row-{{$i}}-color4" class="form-control">
                                @if(@$sampleArticle && isset($sample_wp_specs_array[$i-1]) && $sample_wp_array[$i-1] !== 'default'))
                                @if(@$sampleArticle)
                                    @foreach($warehouseProductSpecsArray[$sample_wp_array[$i-1]] as $key => $color)
                                        <option value="{{$key}}"
                                                {{$key == $sample_wp_specs_array[$i-1][3]
                                                ? 'selected' : ''}}>
                                            {{$color}}
                                        </option>
                                    @endforeach
                                    <option value="default"
                                            {{(!isset($sample_wp_specs_array[$i-1]) || $sample_wp_specs_array[$i-1][3] == 'default') ?
                                            'selected' : ''}}></option>
                                @else
                                    @foreach(@$warehouseFirstWireSpecs as $firstWireSpecs)
                                        <option value="{{$firstWireSpecs->id}}">
                                            {{$firstWireSpecs->color}}
                                        </option>
                                    @endforeach
                                @endif
                                @else
                                    <option value="default" {{@$sampleArticle && $step->id == $sample_steps_array[$i-1] ? 'selected' : ''}}></option>
                                @endif
                            </select>
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-2"></div>
                <div class=" form-group col-md-1">
                    <label>Total de Gramas:</label>
                </div>
                <div class=" form-group col-md-2">
                    <input type="text" class="form-control total_gramas" value="0" readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3"></div>
                <div class="submit-buttons form-group col-md-6" style="margin-top:0; margin-bottom:10px !important;">
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

        //Contar total de gramas da amostra
        console.log("a");

        calcTotalGrams();
        $(".sum_grams").keyup(function () {
            calcTotalGrams();
        });
        $(".sum_grams").change(function () {
            calcTotalGrams();
        });

        function calcTotalGrams() {
            let sumGrams = 0;
            $(".sum_grams").each( function (){
                let toSum = parseInt($(this).val());
                if(isNaN(toSum)){
                    toSum = 0;
                }
                sumGrams += toSum;
            });
            $(".total_gramas").val(sumGrams);
        }


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
                    $( "#row-"+rowSelected+"-color1, #row-"+rowSelected+"-color2, #row-"+rowSelected+"-color3, #row-"+rowSelected+"-color4")
                        .append('<option value="default"></option>');
                    $.each(result, function (key, value) {
                        $( "#row-"+rowSelected+"-color1, #row-"+rowSelected+"-color2, #row-"+rowSelected+"-color3, #row-"+rowSelected+"-color4")
                            .append('<option value="'+value['id']+'">'+value['name']+'</option>');
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
                $("#row-"+rowSelected+"-guiafios").val("");
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
