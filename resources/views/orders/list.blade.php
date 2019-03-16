@extends('layouts.app')

@section('content')

    <div class="container">
        @include('flash::message')

        <h2>Lista de Encomendas</h2>
        <table class="table table-striped thead-dark" role="table">
            <thead role="rowgroup">
            <tr role="row">
                <th role="columnheader">Cliente</th>
                <th role="columnheader">Nº Encomenda</th>
                <th role="columnheader">Amostra INDMEI</th>
                <th role="columnheader">Foto</th>
                <th role="columnheader">Criado Em</th>
                <th role="columnheader">Data de Entrega</th>
                <th role="columnheader">Criado Por</th>
                <th role="columnheader">Status</th>
                @if (Auth::user()->hasAnyRole(['1', '3', '4', '7']) && $view == 'orders')
                <th role="columnheader"></th>
                <th role="columnheader"></th>
                @endif
                @if(Auth::user()->hasAnyRole(['1', '3', '4', '7']) && in_array($view, ['production', 'orders']))
                <th role="columnheader"></th>
                @endif
                {{--@if (Auth::user()->hasAnyRole(['1', '6']))--}}
                {{--<th role="columnheader"></th>--}}
                {{--@endif--}}
                @if (Auth::user()->hasAnyRole(['1', '7']) && $view == 'quotations')
                    <th role="columnheader"></th>
                @endif
            </tr>
            </thead>
            <tbody role="rowgroup">
            @foreach($orders as $order)
                <tr role="row">
                    <td role="columnheader" data-col1="Cliente">{{$order->client->client}}</td>
                    <td role="columnheader" data-col2="Nº de Encomenda">{{$order->client_identifier}}</td>
                    <td role="columnheader" data-col3="Id da Amostra">{{@$order->sampleArticle->reference}}</td>
                    <td role="columnheader" data-col4="Foto">@if(@$order->sampleArticle->image_url)<img width="60" src="../../storage/{{@$order->sampleArticle->image_url}}">@endif</td>
                    <td role="columnheader" data-col5="Criado Em">{{substr($order->created_at, 0, 10)}}</td>
                    <td role="columnheader" data-col6="Data de Entrega">{{$order->delivery_date}}</td>
                    <td role="columnheader" data-col7="Criado Por">{{$order->user->name}}</td>
                    <td role="columnheader" data-col8="Status">{{$order->status->status}}</td>
                    @if (Auth::user()->hasAnyRole(['1', '3', '4', '7']) && $view == 'orders')
                    <td role="columnheader" data-col9="">
                        <form method="get" action="{{url('orders/edit/'.$order->id)}}" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>
                    </td>
                    <td role="columnheader" data-col10="">
                        <button type="button" data-id="{{$order->id}}" data-role="{{$order->client_identifier}}"  class="apagarform btn btn-danger">Apagar</button>
                    </td>
                    @endif
                    @if (Auth::user()->hasAnyRole(['1', '3', '4', '7']) && in_array($view, ['production', 'orders']))
                    <td role="columnheader" data-col11="">
                        <form method="get" action="{{url('/order/production/insert/'.$order->id.'/'.Auth()->id())}}" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-info">Produção Atual</button>
                        </form>
                    </td>
                    @endif
                    {{--@if (Auth::user()->hasAnyRole(['1', '6']))--}}
                    {{--<td role="columnheader" data-col10="">--}}
                        {{--<form method="get" action="{{url('/order/production/insert/'.$order->id.'/')}}" enctype="multipart/form-data">--}}
                            {{--<button type="submit" class="btn btn-info">A minha produção</button>--}}
                        {{--</form>--}}
                    {{--</td>--}}
                    {{--@endif--}}
                    @if (Auth::user()->hasAnyRole(['1', '7']) && $view == 'quotations')
                        <td role="columnheader" data-col11="">
                            <form method="get" action="{{url('/quotation/edit/'.$order->id.'/')}}" enctype="multipart/form-data">
                                <button type="submit" class="btn btn-success">Ver Orçamentação</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Status</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </tfoot>
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
            let table = $('table').DataTable({
                columnDefs: [ { orderable: false, targets: [3, -1, -2, -3] } ],
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
                },
                initComplete: function () {
                    this.api().columns().every( function () {
                        let column = this;
                        console.log(column[0][0]);
                        if(column[0][0] === 7) {
                            let select = $('<select style="max-width:50%;"><option value=""></option></select>')
                                .appendTo( $(column.footer()).empty() )
                                .on( 'change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search( val ? '^'+val+'$' : '', true, false )
                                        .draw();
                                } );

                            column.data().unique().sort().each( function ( d, j ) {
                                select.append( '<option value="'+d+'">'+d+'</option>' )
                            } );
                        }
                    } );
                }
            });

            let filteredData = table
                .column( 7 )
                .data()
                .filter( function ( value, index ) {
                    return value == 'Produzido' ? true : false;
                } );


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