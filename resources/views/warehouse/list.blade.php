@extends('layouts.app')

@section('content')

    <div class="container">
        @include('flash::message')

        <h2>Stock de produtos disponíveis</h2>
        <table class="table table-striped thead-dark">
            <thead>
            <tr>
                <th>Referencia</th>
                <th>Cor</th>
                <th>Quantidade (gramas)</th>
                <th>Atualizado Por</th>
                <th>Última Atualização</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($stock as $product)
                <tr>
                    <td>{{$product->product->reference}}</td>
                    <td>{{$product->color}}</td>
                    <td>{{$product->weight}}</td>
                    <td>{{$product->product->user->name}}</td>
                    <td>{{$product->updated_at}}</td>
                    <td>
                        <form method="get" action="{{url('samples/edit/'.$product->id)}}" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>
                    </td>
                    <td>
                        <button type="button" data-id="{{$product->id}}" data-role="{{$product->reference}}"  class="apagarform btn btn-danger">Apagar</button>
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
                $(".modal-body").append('<p>Amostra de Artigo: ' + name + '</p>');
                $('#apagar').attr('action', 'delete/'+id);
                $("#modalApagar").modal('show');
            });
        });
    </script>

@endsection