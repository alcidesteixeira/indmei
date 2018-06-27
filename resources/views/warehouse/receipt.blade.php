@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dar Entrada de Stock</h2><br/>
    <form id="addRow" method="" action="" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="form-group col-md-3">
                <label for="EntradaSaida">Entrada/Saída:</label>
                <select class="form-control" id="inout" name="inout">
                    <option value="IN">Entrada</option>
                    <option value="OUT">Saída</option>
                </select>
            </div>
            <div class="form-group col-md-6"></div>
            <div class="form-group col-md-3">
                <button type="button" id="newMaterial" class="btn btn-default" style="margin-top: 31px; float:right;">Criar nova Matéria-Prima</button>
            </div>
        </div>

        <div class="row new-material hide">
            <div class="col-md-3"></div>
            <div class="form-group col-md-6">
                <h2>Nova entrada de matéria-prima:</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="form-group col-md-6">
                <label for="Description">Descrição:</label>
                <input type="text" class="form-control" id="description" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="form-group col-md-6">
                <label for="Reference">Referência:</label>
                <input type="text" class="form-control" id="reference" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="form-group col-md-6">
                <label for="color">Cor:</label>
                <input type="text" class="form-control" id="color" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="form-group col-md-6">
                <label for="weight">Peso (Kg):</label>
                <input type="text" class="form-control" id="qtd" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="form-group col-md-6">
                <label for="cost">Custo por Kg:</label>
                <input type="text" class="form-control" id="cost" required>
            </div>
        </div>

        <div class="row new-material hide">
            <div class="col-md-3"></div>
            <div class="form-group col-md-6">
                <label for="threshold">Valor de aviso mínimo:</label>
                <input type="text" class="form-control" id="threshold">
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="submit-buttons form-group col-md-6" style="margin-top:60px">
                <button type="submit" class="btn btn-warning">Adicionar</button>
                <button type="button" onclick="window.history.back();" class="btn btn-info">Voltar</button>
            </div>
        </div>
    </form>

    <form method="post" id="saveReceipt" action="{{url('stock/receipt/')}}" enctype="multipart/form-data">
        @csrf
        <table class="table table-striped thead-dark receipt-table" role="table">
            <thead role="rowgroup">
                <tr role="row">
                    <th role="columnheader">Entrada/Saída</th>
                    <th role="columnheader">Referencia</th>
                    <th role="columnheader">Cor</th>
                    <th role="columnheader">Qtd (Kg)</th>
                    <th role="columnheader">Custo (€/Kg)</th>
                    <th role="columnheader">Descrição</th>
                    <th role="columnheader">Limite mínimo</th>
                </tr>
            </thead>
            <tbody role="rowgroup">
            </tbody>
        </table>


        <div class="row" style="margin-bottom: 30px;">

            <div class="form-group col-md-6">
                <label for="receipt">Carregar Fatura:</label>
                <input type="file" class="form-control-file" name="receipt" id="receipt" required>
            </div>

            <div class="col-md-3"></div>
            <div class="submit-buttons form-group col-md-3">
                <button type="submit" class="btn btn-success" onclick="beforeInput();">Finalizar</button>
            </div>
        </div>
    </form>

</div>


<script>

    function beforeInput ()
    {
        let rowCount = $('table tr').length - 1;
        $("#saveReceipt").append('<input type="hidden" name="rowCount" value="'+rowCount+'">');
    }

    $( function() {
        let availableProducts = {!! json_encode($allProducts) !!};
        let availableColors = {!! json_encode($allColors) !!};

        $( "#reference" ).autocomplete({
            source: availableProducts
        });
        $( "#color" ).autocomplete({
            source: availableColors
        });
    } );

    //Add new product opens new field
    $( "#newMaterial" ).click( function () {
        $(".new-material").toggleClass('hide');
    });


    //Add stock to table

    let i = 1;
    $( "#addRow" ).submit( function (e) {

        e.preventDefault(e);

        let inout = $("#inout").val(); let description = $("#description").val();
        let reference = $("#reference").val(); let color = $("#color").val();
        let qtd = $("#qtd").val(); let cost = $("#cost").val();
        let threshold = $("#threshold").val(); let receipt = $("#receipt").val();

        $( 'tbody' ).append('<tr role="row">' +
            '<td role="columnheader"><input type="text" data-col1="Entrada/Saída" name="inout-'+i+'" value="'+inout+'"></td>' +
            '<td role="columnheader"><input type="text" data-col2="Referência" name="reference-'+i+'" value="'+reference+'"></td>' +
            '<td role="columnheader"><input type="text" data-col3="Cor" name="color-'+i+'" value="'+color+'"></td>' +
            '<td role="columnheader"><input type="text" data-col4="Qtd (Kg)" name="qtd-'+i+'" value="'+qtd+'"></td>' +
            '<td role="columnheader"><input type="text" data-col5="Custo (€/Kg)" name="cost-'+i+'" value="'+cost+'"></td>' +
            '<td role="columnheader"><input type="text" data-col6="Descrição" name="description-'+i+'" value="'+description+'"></td>' +
            '<td role="columnheader"><input type="text" data-col7="Limite mínimo" name="threshold-'+i+'" value="'+threshold+'"></td>' +
            '</tr>');

        i++;
        //Empty values
        $("#description").val(""); $("#reference").val(""); $("#color").val("");
        $("#qtd").val(""); $("#cost").val(""); $("#threshold").val("");

    });

</script>

@endsection