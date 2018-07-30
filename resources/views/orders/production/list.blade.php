@extends('layouts.app')

@section('content')

    <div class="container">
        @include('flash::message')

        <h2>Lista Encomendas em Produção</h2>
        <table class="table table-striped thead-dark" role="table">
            <thead role="rowgroup">
            <tr role="row">
                <th role="columnheader">Cliente</th>
                <th role="columnheader">Id do Cliente</th>
                <th role="columnheader">Id da Amostra</th>
                <th role="columnheader">Descrição</th>
                <th role="columnheader">Data de Entrega</th>
                <th role="columnheader">Produzido Por</th>
                <th role="columnheader"></th>
            </tr>
            </thead>
            <tbody role="rowgroup">
            @foreach($orders as $order)
                <tr role="row">
                    <td role="columnheader" data-col1="Cliente">{{$order->reference}}</td>
                    <td role="columnheader" data-col2="Id do Cliente">{{$order->description}}</td>
                    <td role="columnheader" data-col3="Id da Amostra"></td>
                    <td role="columnheader" data-col4="Descrição">{{$order->user->name}}</td>
                    <td role="columnheader" data-col5="Data de Entrega">{{$order->updated_at}}</td>
                    <td role="columnheader" data-col6="Produzido Por">{{$order->sampleArticleStatus->status}}</td>
                    <td role="columnheader" data-col7="">
                        <form method="get" action="{{url('samples/edit/'.$sample->id)}}" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
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
        });
    </script>

@endsection