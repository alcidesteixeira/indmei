@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Entrada de Encomenda</h2><br/>
    <form method="post" action="{{url('stock/insertReceipt/')}}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="form-group col-md-3">
                <label for="EntradaSaida">Entrada/Saída:</label>
                <select class="form-control" id="inout" name="inout">
                    <option value="IN">Entrada</option>
                    <option value="OUT">Saída</option>
                </select>
            </div>
            <div class="form-group col-md-9">
                <button type="button" class="btn btn-default" style="margin-top: 31px; float:right;">Criar nova Matéria-Prima</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="form-group col-md-6">
                <label for="Description">Descrição:</label>
                <input type="text" class="form-control" name="description" id="description" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="form-group col-md-6">
                <label for="Reference">Referência:</label>
                <input type="text" class="form-control" name="reference" id="reference" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="form-group col-md-6">
                <label for="color">Cor:</label>
                <input type="text" class="form-control" name="color" id="color" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="form-group col-md-6">
                <label for="weight">Quantidade (gramas):</label>
                <input type="text" class="form-control" name="weight" id="qtd" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="form-group col-md-6">
                <label for="receipt">Carregar Fatura:</label>
                <input type="text" class="form-control" name="receipt" id="receipt" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="submit-buttons form-group col-md-6" style="margin-top:60px">
                <button type="button" id="add" class="btn btn-warning">Adicionar</button>
                <button type="submit" class="btn btn-success">Finalizar</button>
                <button type="button" onclick="window.history.back();" class="btn btn-info">Voltar</button>
            </div>
        </div>
    </form>

    <table class="table table-striped thead-dark">
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

</div>


<script>

    //Filter and order table
    $('table').DataTable({
        "ordering": false,
        "pageLength": 25
    });

    $( function() {
        let availableOpts = [
            "ActionScript",
            "AppleScript",
            "Asp",
            "BASIC",
            "C",
            "C++",
            "Clojure",
            "COBOL",
            "ColdFusion",
            "Erlang",
            "Fortran",
            "Groovy",
            "Haskell",
            "Java",
            "JavaScript",
            "Lisp",
            "Perl",
            "PHP",
            "Python",
            "Ruby",
            "Scala",
            "Scheme"
        ];
        $( "#reference" ).autocomplete({
            source: availableOpts
        });
    } );

    //Add stock to table
    $( "#add" ).click( function () {
        let inout = $("#inout").val(); let description = $("#description").val();
        let reference = $("#reference").val(); let color = $("#color").val();
        let qtd = $("#qtd").val(); let minlim = $("#minlim").val();
        let receipt = $("#receipt").val();

        $( 'tbody' ).append('<tr><td>'+inout+'</td><td>'+description+'</td><td>'+reference+'</td><td>'+color+'</td>' +
            '<td>'+qtd+'</td><td>'+minlim+'</td><td>'+receipt+'</td></tr>');
    });

</script>

@endsection