@extends('layouts.app')

@section('content')

    <div class="container">
        @include('flash::message')

        <h2>Lista de Amostras de Artigos disponíveis</h2>
        <table class="table table-striped thead-dark">
            <thead>
            <tr>
                <th>Referencia</th>
                <th>Descrição</th>
                <th>Imagem</th>
                <th>Executante</th>
                <th>Última Atualização</th>
                <th>Status</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($sampleArticles as $sample)
                <tr>
                    <td>{{$sample->reference}}</td>
                    <td>{{$sample->description}}</td>
                    <td><img src="{{$sample->image_url}}" width="200"></td>
                    <td>{{$sample->user->name}}</td>
                    <td>{{$sample->updated_at}}</td>
                    <td>{{$sample->sampleArticleStatus->status}}</td>
                    <td>
                        <form method="get" action="{{url('samples/edit/'.$sample->id)}}" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>
                    </td>
                    <td>
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
                "pageLength": 25
            });


            $('.apagarform').click(function() {
                let id = $( this ).data('id');
                let name = $( this ).data('role');
                $(".modal-body").append('');
                $(".modal-body").append('<p>Amostra: ' + name + '</p>');
                $('#apagar').attr('action', 'delete/'+id);
                $("#modalApagar").modal('show');
            });
        });
    </script>

@endsection