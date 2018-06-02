@extends('layouts.app')

@section('content')

<div class="container">
    @include('flash::message')

    <h2>Lista de Roles disponíveis</h2>
    <table class="table table-striped thead-dark">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Data de Alteração</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td>{{$role->name}}</td>
                <td>{{$role->description}}</td>
                <td>{{$role->updated_at}}</td>
                <td>
                    <form method="get" action="{{url('roles/edit/'.$role->id)}}" enctype="multipart/form-data">
                        <button type="submit" class="btn btn-warning">Editar</button>
                    </form>
                </td>
                <td>
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