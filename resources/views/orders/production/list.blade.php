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
                    <td role="columnheader" data-col1="Cliente">{{$order->order()->first()->client->client}}</td>
                    <td role="columnheader" data-col2="Id do Cliente">{{$order->order()->first()->client_id}}</td>
                    <td role="columnheader" data-col3="Id da Amostra">{{$order->order()->first()->sample_article_id}}</td>
                    <td role="columnheader" data-col4="Descrição">{{$order->order()->first()->description}}</td>
                    <td role="columnheader" data-col5="Data de Entrega">{{$order->order()->first()->delivery_date}}</td>
                    <td role="columnheader" data-col6="Produzido Por">{{$order->user()->first()->name}}</td>
                    <td role="columnheader" data-col7="">
                        <form method="get" action="{{url('/order/production/insert/'.$order->order()->first()->id.'/'.$order->user()->first()->id)}}" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-info">Ver</button>
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