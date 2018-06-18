@extends('layouts.app')

@section('content')

    <div class="container">
        @include('flash::message')

        <h2>Lista de Amostras de Artigos disponíveis</h2>
        <table class="table table-striped thead-dark" role="table">
            <thead role="rowgroup">
            <tr role="row">
                <th role="columnheader">Referencia</th>
                <th role="columnheader">Descrição</th>
                <th role="columnheader">Imagem</th>
                <th role="columnheader">Executante</th>
                <th role="columnheader">Última Atualização</th>
                <th role="columnheader">Status</th>
                <th role="columnheader"></th>
                <th role="columnheader"></th>
            </tr>
            </thead>
            <tbody role="rowgroup">
            @foreach($sampleArticles as $sample)
                <tr role="row">
                    <td role="columnheader">{{$sample->reference}}</td>
                    <td role="columnheader">{{$sample->description}}</td>
                    <td role="columnheader"><img src="../../storage/{{$sample->image_url}}" width="50"></td>
                    <td role="columnheader">{{$sample->user->name}}</td>
                    <td role="columnheader">{{$sample->updated_at}}</td>
                    <td role="columnheader">{{$sample->sampleArticleStatus->status}}</td>
                    <td role="columnheader">
                        <form method="get" action="{{url('samples/edit/'.$sample->id)}}" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>
                    </td>
                    <td role="columnheader">
                        <button type="button" data-id="{{$sample->id}}" data-role="{{$sample->description}}"  class="apagarform btn btn-danger">Apagar</button>
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
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });


            $('.apagarform').click(function() {
                let id = $( this ).data('id');
                let name = $( this ).data('role');
                $(".modal-body").append('');
                $(".modal-body").append('<p>Amostra de Artigo: ' + name + '</p>');
                $('#apagar').attr('action', 'delete/'+id);
                $("#modalApagar").modal('show');
            });
        });
    </script>

@endsection