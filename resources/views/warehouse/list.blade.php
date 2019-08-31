@extends('layouts.app')

@section('content')

    <div class="container">
        @include('flash::message')

        <h2>Stock de Fios</h2>
        <table class="table table-striped thead-dark" id="stock" role="table">
            <thead role="rowgroup">
            <tr role="row">
                <th role="columnheader">Referência INDMEI</th>
                <th role="columnheader">Cor</th>
                <th role="columnheader" title="Stock em armazém subtraíndo o valor produzido diariamente pelos operadores">Stock Bruto (Kg)</th>
                <th role="columnheader" title="Stock em armazém subtraíndo o valor necessário para as encomendas criadas">Stock Líquido (Kg)</th>
                <th role="columnheader" title="Stock necessário para as encomendas em estado Por Produzir">Stock Por Produzir (Kg)</th>
                <th role="columnheader" title="Tempo de entrega estimado">Entrega (dias)</th>
                <th role="columnheader">Pedido (Kg)</th>
                <th role="columnheader">Custo (€/Kg)</th>
                <th role="columnheader">Atualizado Por</th>
                <th role="columnheader">Última Atualização</th>
                <th role="columnheader"></th>
                <th role="columnheader"></th>
                <th role="columnheader"></th>
            </tr>
            </thead>
            <tbody role="rowgroup">
            @foreach($stock as $product)
                {{--Requested Stock History--}}
                @php($email_content = '')
                @php($count=0)
                @php($total_stock_requested = 0)
                @foreach($stock_request_history as $stock_request)
                    @if($product->id == $stock_request->warehouse_product_spec_id)
                        @php ($email_content .= 'Pedido: '.$stock_request->amount_requested.'Kg; '.
                            'Data: '.substr($stock_request->created_at, 0, 10).'| ' ?: 0)
                        @php($count++)
                        @php($total_stock_requested += $stock_request->amount_requested)
                    @endif
                @endforeach
                {{--Stock IN History--}}
                @php($stock_in_latest = '')
                @php($count=0)
                @php($total_stock_in = 0)
                @foreach($stock_history as $stock_in)
                    @if($product->id == $stock_in->warehouse_product_spec_id)
                        @php($weight = $stock_in->weight / 1000)
                        @php ($stock_in_latest .= 'Entrada: '.$weight.'Kg; '.
                            'Data: '.substr($stock_in->created_at, 0, 10).'| ' ?: 0)
                        @php($total_stock_in += $weight)
                        @php($count++)
                    @endif
                @endforeach
                {{--Difference between requested stock and stock that has entered--}}
                @php($stock_requested_differential = $total_stock_requested-$total_stock_in)
                <tr style="background-color: {{@$stock_requested_differential < $product->liquid_weight ? '#f9a9a9' : ''}}" data-specid="{{$product->id}}" role="row">
                    <td role="columnheader" data-col1="Referência">{{$product->product->reference}}</td>
                    <td role="columnheader" data-col2="Cor">{{$product->color}}</td>
                    <td role="columnheader" data-col3="Stock Bruto (Kg)" title="{{$stock_in_latest}}">{{$product->gross_weight / 1000}}</td>
                    <td role="columnheader" data-col4="Stock Líquido (Kg)">{{$product->liquid_weight / 1000}}</td>
                    <td role="columnheader" data-col5="Stock Por Porduzir (Kg)">{{$product->to_do_weight / 1000}}</td>
                    <td role="columnheader" data-col6="Entrega (dias)">{{$product->threshold}}</td>
                    <td role="columnheader" data-col7="Pedido (Kg)" title="{{$email_content}}">{{$product->stockRequested['amount_requested'] && $stock_requested_differential > 0 ? $stock_requested_differential: 0}}</td>
                    <td role="columnheader" data-col8="Custo (€/Kg)">{{$product->cost}}</td>
                    <td role="columnheader" data-col9="Atualizado Por">{{$product->product->user->name}}</td>
                    <td role="columnheader" data-col10="Última Atualização">{{$product->updated_at}}</td>
                    <td role="columnheader" data-col11="">
                        <form method="get" action="{{url('stock/edit/'.$product->id)}}" class="edit" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>
                    </td>
                    <td role="columnheader" data-col12="">
                        <button type="button" data-id="{{$product->id}}" data-role="{{$product->product->reference}}" class="delete apagarform btn btn-danger">Apagar</button>
                    </td>
                    <td role="columnheader" data-col13="">
                        {{--@if($product->threshold*1000 >= $product->liquid_weight)--}}
                        <form method="get" action="{{url('/email/create/'.$product->id)}}" class="email" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-success">Pedir stock</button>
                        </form>
                        {{--@endif--}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div id="history" class="modal fade" role="dialog">
        <div class="modal-dialog" style="max-width: 850px;">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Histórico de Matéria-Prima</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="container stock-history" style="margin-top: 50px;margin-bottom: 50px">
                            <table class="table table-striped thead-dark table-responsive" role="table">
                                <thead role="rowgroup">
                                <tr role="row">
                                    <th role="columnheader">Id da Encomenda</th>
                                    <th role="columnheader">Descrição</th>
                                    <th role="columnheader">Peso(Kg)</th>
                                    <th role="columnheader">Data de Entrega</th>
                                    <th role="columnheader">Anexo</th>
                                </tr>
                                </thead>
                                <tbody role="rowgroup">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>

        </div>
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>

        </div>
    </div>

    <script>

        $( document ).ready( function () {
            //Filter and order table
            let table = $('#stock').DataTable({
                columnDefs: [ { orderable: false, targets: [-1, -2,-3] } ],
                "pageLength": 10,
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

        // $('.apagarform').click(function() {
        //     let id = $( this ).data('id');
        //     let name = $( this ).data('role');
        //     $(".modal-body").html('');
        //     $(".modal-body").append('<p>Matéria-prima: ' + name + '</p>');
        //     $('#apagar').attr('action', 'delete/'+id);
        //     $("#modalApagar").modal('show');
        // });

        $('#stock tbody .apagarform').on('click', function () {
            let id = $( this ).data('id');
            let name = $( this ).data('role');
            $(".modal-body").html('');
            $(".modal-body").append('<p>Matéria-prima: ' + name + '</p>');
            $('#apagar').attr('action', 'delete/'+id);
            $("#modalApagar").modal('show');
        });

        //Select table row from stock to show details
        //$(".stock-history").css('display', 'none');

        $("#stock tbody tr").click(function(){
            $(this).addClass('selected').siblings().removeClass('selected');
            let value=$(this).data('specid');
            //alert(value);
            $.ajax({
                url: "/stock/list/historic/"+value,
            }).done(function(data) {
                console.log(data);
                let toAppend = '';
                $.each(data, function(k,v) {
                    let ident = '';
                    if(v["client_identifier_public"]) {
                        ident = v["client_identifier_public"];
                    } else {
                        ident = v["client_identifier"];
                    }
                    toAppend = toAppend + '<tr role="row">' +
                        '<td role="columnheader" data-col1="Id da encomenda">'+ident+'</td>' +
                        '<td role="columnheader" data-col2="Descrição">'+v['description']+'</td>' +
                        '<td role="columnheader" data-col3="Peso(Kg)">'+(v["sum_weight"] / 1000).toFixed(2)+'</td>' +
                        '<td role="columnheader" data-col4="Data de Entrega">'+v["delivery_date"]+'</td>' +
                        '<td role="columnheader" data-col4="Anexo"><img style="max-width: 50px" src="../../storage/'+v["receipt"]+'" ></td></tr>';
                });
                $(".stock-history tbody").empty();
                $(".stock-history tbody").append(toAppend);
            });
            //$(".stock-history").css('display', 'inherit');
            $("#history").modal('show');
        });

        $('.edit, .delete, .email').click(function(event){
            event.stopPropagation();
        });


    </script>

@endsection
