@extends('layouts.app')

@section('content')

    <div class="container">
        @include('flash::message')

        <h2>Lista de Encomendas</h2>
        <table class="table table-striped thead-dark" role="table">
            <thead role="rowgroup">
            <tr role="row">
                <th role="columnheader">Cliente</th>
                <th role="columnheader">Id do Cliente</th>
                <th role="columnheader">Id da Amostra</th>
                <th role="columnheader">Descrição</th>
                <th role="columnheader">Data de Entrega</th>
                <th role="columnheader">Criado Por</th>
                @if (Auth::user()->hasAnyRole(['1', '4']))
                <th role="columnheader"></th>
                <th role="columnheader"></th>
                <th role="columnheader"></th>
                @endif
                @if (Auth::user()->hasAnyRole(['1', '6']))
                <th role="columnheader"></th>
                @endif
                @if (Auth::user()->hasAnyRole(['1', '7']))
                    <th role="columnheader"></th>
                @endif
            </tr>
            </thead>
            <tbody role="rowgroup">
            @foreach($orders as $order)
                <tr role="row">
                    <td role="columnheader" data-col1="Cliente">{{$order->client->client}}</td>
                    <td role="columnheader" data-col2="Id do Cliente">{{$order->client_identifier}}</td>
                    <td role="columnheader" data-col3="Id da Amostra">{{@$order->sampleArticle->reference}}</td>
                    <td role="columnheader" data-col4="Descrição">{{$order->description}}</td>
                    <td role="columnheader" data-col5="Data de Entrega">{{$order->delivery_date}}</td>
                    <td role="columnheader" data-col6="Criado Por">{{$order->user->name}}</td>
                    @if (Auth::user()->hasAnyRole(['1', '4']))
                    <td role="columnheader" data-col7="">
                        <form method="get" action="{{url('orders/edit/'.$order->id)}}" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>
                    </td>
                    <td role="columnheader" data-col8="">
                        <button type="button" data-id="{{$order->id}}" data-role="{{$order->client_identifier}}"  class="apagarform btn btn-danger">Apagar</button>
                    </td>
                    <td role="columnheader" data-col9="">
                        <form method="get" action="{{url('/order/production/'.$order->id)}}" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-info">Produção Atual</button>
                        </form>
                    </td>
                    @endif
                    @if (Auth::user()->hasAnyRole(['1', '6']))
                    <td role="columnheader" data-col8="">
                        <form method="get" action="{{url('/order/production/insert/'.$order->id.'/')}}" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-info">A minha produção</button>
                        </form>
                    </td>
                    @endif
                    @if (Auth::user()->hasAnyRole(['1', '7']))
                        <td role="columnheader" data-col8="">
                            <form method="get" action="{{url('/quotation/edit/'.$order->id.'/')}}" enctype="multipart/form-data">
                                <button type="submit" class="btn btn-primary">Ver Orçamentação</button>
                            </form>
                        </td>
                    @endif
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
                $(".modal-body").text('');
                $(".modal-body").append('<p>Encomenda com Id do Cliente: ' + name + '</p>');
                $('#apagar').attr('action', 'delete/'+id);
                $("#modalApagar").modal('show');
            });
        });
    </script>

@endsection