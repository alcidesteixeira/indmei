@extends('layouts.app')

@section('content')

    <div class="container">
        @include('flash::message')

        <h2>Lista de Amostras</h2>
        <table class="table table-striped thead-dark" role="table">
            <thead role="rowgroup">
            <tr role="row">
                <th role="columnheader">Amostra INDMEI</th>
                <th role="columnheader">Descrição</th>
                <th role="columnheader">Imagem</th>
                <th role="columnheader">Executante</th>
                <th role="columnheader">Última Atualização</th>
                <th role="columnheader"></th>
                <th role="columnheader"></th>
                <th role="columnheader"></th>
            </tr>
            </thead>
            <tbody role="rowgroup">
            @foreach($sampleArticles as $sample)
                <tr role="row">
                    <td role="columnheader" data-col1="Referência">{{$sample->reference}}</td>
                    <td role="columnheader" data-col2="Descrição">{{$sample->description}}</td>
                    <td role="columnheader" data-col3="Imagem"><img src="../../storage/{{$sample->image_url}}" width="50"></td>
                    <td role="columnheader" data-col4="Executante">{{$sample->user->name}}</td>
                    <td role="columnheader" data-col5="Última Atualização">{{$sample->updated_at}}</td>
                    <td role="columnheader" data-col7="">
                        <form method="get" action="{{url('samples/edit/'.$sample->id)}}" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>
                    </td>
                    <td role="columnheader" data-col8="">
                        <button type="button" data-id="{{$sample->id}}" data-role="{{$sample->description}}"  class="apagarform btn btn-danger">Apagar</button>
                    </td>
                    <td role="columnheader" data-col9="">
                        <form method="get" action="{{url('samples/getForDuplicate/'.$sample->id)}}" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-info">Duplicar</button>
                        </form>
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
            $('table').DataTable({
                columnDefs: [ { orderable: false, targets: [-1, -2] } ],
                "pageLength": 25,
                dom: 'lBfrtip',
                buttons: [
                    { extend: 'csv', text: 'CSV' },
                    { extend: 'excel', text: 'Excel' },
                    { extend: 'pdf', text: 'PDF' },
                    { extend: 'print', text: 'Imprimir' }
                ],
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


            $('.table tbody').on('click', '.apagarform', function () {
                let id = $( this ).data('id');
                let name = $( this ).data('role');
                $(".modal-body").html('');
                $(".modal-body").append('<p>Amostra de Artigo: ' + name + '</p>');
                $('#apagar').attr('action', 'delete/'+id);
                $("#modalApagar").modal('show');
            });
        });
    </script>

@endsection