@extends('layouts.app')

@section('content')

<div class="container">
    @include('flash::message')



    <h2>Lista de Roles disponíveis</h2>
    <table class="table table-striped thead-dark" role="table">
        <thead role="rowgroup">
        <tr role="row">
            <th role="columnheader">Nome</th>
            <th role="columnheader">Descrição</th>
            <th role="columnheader">Data de Alteração</th>
            <th role="columnheader"></th>
            <th role="columnheader"></th>
        </tr>
        </thead>
        <tbody role="rowgroup">
            @foreach($roles as $role)
            <tr role="row">
                <td role="columnheader" data-col1="Nome">{{$role->name}}</td>
                <td role="columnheader" data-col2="Descrição">{{$role->description}}</td>
                <td role="columnheader" data-col3="Data de Alteração">{{$role->updated_at}}</td>
                <td role="columnheader" data-col4="">
                    <form method="get" action="{{url('roles/edit/'.$role->id)}}" enctype="multipart/form-data">
                        <button type="submit" class="btn btn-warning">Editar</button>
                    </form>
                </td>
                <td role="columnheader" data-col5="">
                    <button type="button" data-id="{{$role->id}}" data-role="{{$role->name}}"  class="apagarform btn btn-danger">Apagar</button>
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


        $('.apagarform').click(function() {
            let id = $( this ).data('id');
            let name = $( this ).data('role');
            $(".modal-body").append('');
            $(".modal-body").append('<p>Role: ' + name + '</p>');
            $('#apagar').attr('action', 'delete/'+id);
            $("#modalApagar").modal('show');
        });
    });
</script>

@endsection