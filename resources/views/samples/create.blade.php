@extends('layouts.app')

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
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-3">
                    <label for="pe">Pé:</label>
                    <input type="text" class="form-control" name="pe" value="{{@$sampleArticle->pe}}" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="Perna">Perna:</label>
                    <input type="text" class="form-control" name="perna" value="{{@$sampleArticle->perna}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-3">
                    <label for="Punho">Punho:</label>
                    <input type="text" class="form-control" name="punho" value="{{@$sampleArticle->punho}}" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="Malha">Malha:</label>
                    <input type="text" class="form-control" name="malha" value="{{@$sampleArticle->malha}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-3">
                    <label for="Maq">Maq:</label>
                    <input type="text" class="form-control" name="maq" value="{{@$sampleArticle->maq}}" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="Forma">Forma:</label>
                    <input type="text" class="form-control" name="forma" value="{{@$sampleArticle->forma}}" required>
                </div>
            </div>


            <table class="table table-striped thead-dark">
                <thead>
                <tr>
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
                @for($i = 1; $i <= sizeof($steps); $i++)
                    <tr>
                        <td>
                            <select size="1" id="row-{{$i}}-step" name="row-{{$i}}-step">
                                @foreach($steps as $step)
                                <option value="{{$step->id}}" {{$step->id == $i ? 'selected' : ''}}>
                                    {{$step->step}}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" id="row-{{$i}}-grams" name="row-{{$i}}-grams" value="{{@$sampleArticle && $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->grams ?
                            $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->grams : ''}}" required>
                        </td>
                        <td>
                            <select size="1" class="referenceChanged" data-row="{{$i}}" id="row-{{$i}}-reference" name="row-{{$i}}-reference">
                                @foreach($warehouseProducts as $product)
                                <option value="{{@$product->id}}" {{@$sampleArticle && $product->id == $sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouse_product_id ? 'selected' : ''}}>
                                    {{@$product->reference}}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select size="1" id="row-{{$i}}-color1" name="row-{{$i}}-color1">
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
                            </select>
                        </td>
                        <td>
                            <select size="1" id="row-{{$i}}-color2" name="row-{{$i}}-color2">
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
                            </select></td>
                        <td>
                            <select size="1" id="row-{{$i}}-color3" name="row-{{$i}}-color3">
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
                            </select>
                        </td>
                        <td>
                            <select size="1" id="row-{{$i}}-color4" name="row-{{$i}}-color4">
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
        $('table').DataTable({
            "ordering": false,
            "pageLength": 25
        });

        function beforeInput ()
        {
            let rowCount = $('table tr').length - 1;
            let colorsCount = $('table tr th').length - 3;
            $("form").append('<input type="hidden" name="rowCount" value="'+rowCount+'">' +
                '<input type="hidden" name="colorsCount" value="'+colorsCount+'">');
        }

        //Whenever user changes wire, queries database to output correct wire colors
        $(" .referenceChanged ").change( function () {
            let wireSelectedId = $( ' option:selected', this).val();
            let rowSelected = $( this ).data('row');
            $.ajax({
                url: "/samples/updatewirespecs/"+wireSelectedId,
                success: function(result){
                    $( "#row-"+rowSelected+"-color1, #row-"+rowSelected+"-color2, #row-"+rowSelected+"-color3, #row-"+rowSelected+"-color4").empty();
                    $.each(result, function (key, value) {
                        $( "#row-"+rowSelected+"-color1, #row-"+rowSelected+"-color2, #row-"+rowSelected+"-color3, #row-"+rowSelected+"-color4")
                            .append('<option value="'+key+'">'+value+'</option>');
                    });
                }});
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