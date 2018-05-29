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
                    <form method="get" action="{{url('roles/delete/'.$role->id)}}" enctype="multipart/form-data">
                        <button type="submit" class="btn btn-danger">Apagar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection