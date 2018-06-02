@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{@$sampleArticle->name ? 'Atualizar Amostra de Artigo' : 'Criar Nova Amostra de Artigo'}}</h2><br/>
        <form method="post" action="{{@$sampleArticle->name ? url('samples/update/'.$sampleArticle->id) : url('samples/create')}}" enctype="multipart/form-data">
            @csrf
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
                    <input type="text" class="form-control" name="image" value="{{@$sampleArticle->image_url}}" required>
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
                        <td><select size="1" id="row-{{$i}}-step" name="row-{{$i}}-step">
                                @foreach($steps as $step)
                                <option value="{{$step->id}}" {{$step->id == $i ? 'selected' : ''}}>
                                    {{$step->step}}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" id="row-{{$i}}-grams" name="row-{{$i}}-grams" value="" required>
                        </td>
                        <td>
                            <select size="1" id="row-{{$i}}-reference" name="row-{{$i}}-reference">
                                @foreach($warehouseProducts as $product)
                                <option value="{{$product->id}}" selected="selected">
                                    {{$product->reference}}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select size="1" id="row-{{$i}}-color1" name="row-{{$i}}-color1">
                                <option value="Edinburgh" selected="selected">
                                    Edinburgh
                                </option>
                            </select>
                        </td>
                        <td>
                            <select size="1" id="row-{{$i}}-color2" name="row-{{$i}}-color2">
                                <option value="Edinburgh" selected="selected">
                                    Edinburgh
                                </option>
                            </select></td>
                        <td>
                            <select size="1" id="row-{{$i}}-color3" name="row-{{$i}}-color3">
                                <option value="Edinburgh" selected="selected">
                                    Edinburgh
                                </option>
                            </select>
                        </td>
                        <td>
                            <select size="1" id="row-{{$i}}-color4" name="row-{{$i}}-color4">
                                <option value="Edinburgh" selected="selected">
                                    Edinburgh
                                </option>
                            </select>
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>


            <div class="row">
                <div class="col-md-3"></div>
                <div class="submit-buttons form-group col-md-6" style="margin-top:60px">
                    <button type="submit" onclick="beforeInput();" class="btn btn-success">{{@$sampleArticle->name ? 'Atualizar' : 'Criar'}}</button>
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

        function beforeInput () {
            let rowCount = $('table tr').length - 1;
            $("form").append('<input type="hidden" name="rowCount" value="'+rowCount+'">');
        }

    </script>

@endsection