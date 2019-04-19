@extends('layouts.app')

@section('content')

    <div class="container">
        @include('flash::message')

        <h2>Lista de Orçamentos</h2>
        <table class="table table-striped thead-dark" role="table">
            <thead role="rowgroup">
            <tr role="row">
                <th role="columnheader">Referência</th>
                <th role="columnheader">Data</th>
                <th role="columnheader">Cliente</th>
                <th role="columnheader">Preço</th>
                <th role="columnheader">Imagem</th>
                <th role="columnheader"></th>
                <th role="columnheader"></th>
            </tr>
            </thead>
            <tbody role="rowgroup">
            @foreach($quotationV2 as $quotation)
                <tr role="row">
                    <td role="columnheader" data-col1="Referência">{{$quotation->reference}}</td>
                    <td role="columnheader" data-col2="Data">{{$quotation->date}}</td>
                    <td role="columnheader" data-col3="Cliente">{{$clients[$quotation->client]}}</td>
                    <td role="columnheader" data-col4="Preço">{{$quotation->client_price}}</td>
                    <td role="columnheader" data-col5="Imagem"><img width="60" src="../../storage/{{@$quotation->product_image}}"></td>
                    <td role="columnheader" data-col6="">
                        <form method="get" action="{{url('quotation/edit/'.$quotation->id)}}" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>
                    </td>
                    <td role="columnheader" data-col7="">
                        <button type="button" data-id="{{$quotation->id}}" data-reference="{{$quotation->reference}}"  class="apagarform btn btn-danger">Apagar</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div id="modalApagar" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tem a certeza que pretende apagar:</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <form method="get" id="apagar" action="" enctype="multipart/form-data">
                        <button type="submit" class="btn btn-info">Apagar</button>
                    </form>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <script>

        $( document ).ready( function () {
            //Filter and order table
            let table = $('table').DataTable({
                columnDefs: [ { orderable: false, targets: [-1, -2] } ],
                "pageLength": 25,
                "language": {
                    "lengthMenu": "Apresentar _MENU_ resultados por página",
                    "zeroRecords": "Nenhum resultado encontrado.",
                    "info": "Página _PAGE_ de _PAGES_",
                    "infoEmpty": "Sem resultados disponíveis",
                    "infoFiltered": "(Filtrado de _MAX_ resultados totais)",
                    "paginate": {
                        "first":      "Primeira",
                        "last":       "Última",
                        "next":       "Seguinte",
                        "previous":   "Anterior"
                    },
                    "loadingRecords": "A pesquisar...",
                    "processing":     "A processar...",
                    "search":         "Pesquisar:",
                }
            });

            $('.table tbody .apagarform').on('click', function () {
                let id = $( this ).data('id');
                let name = $( this ).data('reference');
                $(".modal-body").text('');
                $(".modal-body").append('<p>Orçamento com referência: ' + name + '</p>');
                $('#apagar').attr('action', 'delete/'+id);
                $("#modalApagar").modal('show');
            });
        });
    </script>

@endsection