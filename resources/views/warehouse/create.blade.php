@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{@$stock->reference ? 'Atualizar Amostra de Artigo' : 'Criar Nova Amostra de Artigo'}}</h2><br/>
        <form method="post" action="{{@$stock->reference ? url('samples/update/'.$stock->id) : url('samples/create')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="EntradaSaida">Entrada/Saída:</label>
                    <select class="form-control" id="inout" name="inout">
                        <option value="IN" {{@$stock->inout == 'IN' ? 'selected' : ''}}>
                            Entrada
                        </option>
                        <option value="OUT" {{@$stock->inout == 'OUT' ? 'selected' : ''}}>
                            Saída
                        </option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="Description">Descrição:</label>
                    <input type="text" class="form-control" name="description" value="{{@$stock->description}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="Reference">Referência:</label>
                    <input type="text" class="form-control" name="reference" value="{{@$stock->reference}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="color">Cor:</label>
                    <input type="text" class="form-control" name="color" value="{{@$stock->color}}" required>
                </div></div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label for="weight">Peso (gramas):</label>
                    <input type="text" class="form-control" name="weight" value="{{@$stock->weight}}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3"></div>
                <div class="submit-buttons form-group col-md-6" style="margin-top:60px">
                    <button type="submit" onclick="beforeInput();" class="btn btn-success">{{@$stock->reference ? 'Atualizar' : 'Criar'}}</button>
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

    </script>

@endsection