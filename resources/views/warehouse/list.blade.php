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
                <th role="columnheader" title="Valor limite mínimo que o peso bruto de stock irá disparar">Alerta mínimo (Kg)</th>
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
                <tr style="background-color: {{$product->threshold*1000 >= $product->gross_weight ? '#f9a9a9' : ''}}" data-specid="{{$product->id}}" role="row">
                    <td role="columnheader" data-col1="Referência">{{$product->product->reference}}</td>
                    <td role="columnheader" data-col2="Cor">{{$product->color}}</td>
                    <td role="columnheader" data-col3="Stock Bruto (Kg)">{{$product->gross_weight / 1000}}</td>
                    <td role="columnheader" data-col4="Stock Líquido (Kg)">{{$product->liquid_weight / 1000}}</td>
                    <td role="columnheader" data-col5="Alerta mínimo (Kg)">{{$product->threshold}}</td>
                    <td role="columnheader" data-col6="Custo (€/Kg)">{{$product->cost}}</td>
                    <td role="columnheader" data-col7="Atualizado Por">{{$product->product->user->name}}</td>
                    <td role="columnheader" data-col8="Última Atualização">{{$product->updated_at}}</td>
                    <td role="columnheader" data-col9="">
                        <form method="get" action="{{url('stock/edit/'.$product->id)}}" id="edit" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>
                    </td>
                    <td role="columnheader" data-col10="">
                        <button type="button" data-id="{{$product->id}}" data-role="{{$product->product->reference}}" id="delete" class="apagarform btn btn-danger">Apagar</button>
                    </td>
                    <td role="columnheader" data-col11="">
                        @if($product->threshold*100 >= $product->gross_weight)
                        <form method="get" action="{{url('/email/create/'.$product->id)}}" id="email" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-success">Pedir stock</button>
                        </form>
                        @endif
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
                                    <th role="columnheader">Entrada/Saída</th>
                                    <th role="columnheader">Quantidade (g)</th>
                                    <th role="columnheader">Custo (€)</th>
                                    <th role="columnheader">Descrição</th>
                                    <th role="columnheader">Atualizado Por</th>
                                    <th role="columnheader">Última Atualização</th>
                                    <th role="columnheader">Fatura</th>
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
            $('#stock').DataTable({
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


            $('.apagarform').click(function() {
                let id = $( this ).data('id');
                let name = $( this ).data('role');
                $(".modal-body").append('');
                $(".modal-body").append('<p>Matéria-prima: ' + name + '</p>');
                $('#apagar').attr('action', 'delete/'+id);
                $("#modalApagar").modal('show');
            });
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
                    toAppend = toAppend + '<tr role="row">' +
                        '<td role="columnheader" data-col1="Entrada/Saída">'+v["inout"]+'</td>' +
                        '<td role="columnheader" data-col2="Quantidade (g)">'+v["weight"]+'</td>' +
                        '<td role="columnheader" data-col3="Custo (€)">'+v["cost"]+'</td>' +
                        '<td role="columnheader" data-col4="Descrição">'+v["description"]+'</td>' +
                        '<td role="columnheader" data-col5="Atualizado Por">'+v["name"]+'</td>' +
                        '<td role="columnheader" data-col6="Última Atualização">'+v["created_at"]+'</td>' +
                        '<td role="columnheader" data-col7="Anexo"><a href="../../storage/'+v["receipt"]+'" target="_blank">'+v['receipt'].substr(v['receipt'].length - 3)+'</a></td></tr>';
                });
                $(".stock-history tbody").empty();
                $(".stock-history tbody").append(toAppend);
            });
            //$(".stock-history").css('display', 'inherit');
            $("#history").modal('show');
        });

        $('#edit, #delete, #email').click(function(event){
            event.stopPropagation();
        });


    </script>

@endsection