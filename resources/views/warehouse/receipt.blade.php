@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Entrada de Encomenda</h2><br/>
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

            <div class="form-group col-md-6">
                <label for="receipt">Carregar Fatura:</label>
                <input type="text" class="form-control" id="receipt" required>
            </div>

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
                <label for="weight">Peso (gramas):</label>
                <input type="text" class="form-control" id="qtd" required>
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
        <table class="table table-striped thead-dark receipt-table">
            <thead>
            <tr>
                <th>Entrada/Saída</th>
                <th>Referencia</th>
                <th>Cor</th>
                <th>Qtd (g)</th>
                <th>Descrição</th>
                <th>Limite mínimo</th>
                <th>Fatura</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <div class="row">
            <div class="col-md-9"></div>
            <div class="submit-buttons form-group col-md-3" style="margin-top:60px">
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
        let qtd = $("#qtd").val(); let threshold = $("#threshold").val();
        let receipt = $("#receipt").val();

        $( 'tbody' ).append('<tr>' +
            '<td><input type="text" name="inout-'+i+'" value="'+inout+'"></td>' +
            '<td><input type="text" name="reference-'+i+'" value="'+reference+'"></td>' +
            '<td><input type="text" name="color-'+i+'" value="'+color+'"></td>' +
            '<td><input type="text" name="qtd-'+i+'" value="'+qtd+'"></td>' +
            '<td><input type="text" name="description-'+i+'" value="'+description+'"></td>' +
            '<td><input type="text" name="threshold-'+i+'" value="'+threshold+'"></td>' +
            '<td><input type="text" name="receipt-'+i+'" value="'+receipt+'"></td>' +
            '</tr>');

        i++;
        //Empty values
        $("#description").val(""); $("#reference").val(""); $("#color").val("");
        $("#qtd").val(""); $("#threshold").val("");

    });

</script>

@endsection