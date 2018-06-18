@extends('layouts.app')

@section('content')

    <div class="container">
        @include('flash::message')

        <h2>Stock de produtos disponíveis</h2>
        <table class="table table-striped thead-dark" id="stock" role="table">
            <thead role="rowgroup">
            <tr role="row">
                <th role="columnheader">Referência</th>
                <th role="columnheader">Cor</th>
                <th role="columnheader">Stock Bruto (g)</th>
                <th role="columnheader">Stock Líquido (g)</th>
                <th role="columnheader">Atualizado Por</th>
                <th role="columnheader">Descrição</th>
                <th role="columnheader">Alerta mínimo (g)</th>
                <th role="columnheader">Última Atualização</th>
                <th role="columnheader"></th>
                <th role="columnheader"></th>
            </tr>
            </thead>
            <tbody role="rowgroup">
            @foreach($stock as $product)
                <tr style="background-color: {{$product->threshold >= $product->weight ? '#f9a9a9' : ''}}" data-specid="{{$product->id}}" role="row">
                    <td role="columnheader">{{$product->product->reference}}</td>
                    <td role="columnheader">{{$product->color}}</td>
                    <td role="columnheader">{{$product->weight}}</td>
                    <td role="columnheader">{{$product->weight}}</td>
                    <td role="columnheader">{{$product->product->user->name}}</td>
                    <td role="columnheader">{{$product->description}}</td>
                    <td role="columnheader">{{$product->threshold}}</td>
                    <td role="columnheader">{{$product->updated_at}}</td>
                    <td role="columnheader">
                        <form method="get" action="{{url('stock/edit/'.$product->id)}}" id="edit" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>
                    </td>
                    <td role="columnheader">
                        <button type="button" data-id="{{$product->id}}" data-role="{{$product->product->reference}}" id="delete" class="apagarform btn btn-danger">Apagar</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="container stock-history" style="margin-top: 50px;margin-bottom: 50px">
        <h4>Histórico de Matéria-Prima</h4>
        <table class="table table-striped thead-dark" role="table">
            <thead role="rowgroup">
            <tr role="row">
                <th role="columnheader">Entrada/Saída</th>
                <th role="columnheader">Quantidade (g)</th>
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
            $('#stock').DataTable({
                columnDefs: [ { orderable: false, targets: [-1, -2] } ],
                "pageLength": 25,
                dom: 'lBfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
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

        //Select table row from stock to show details
        $(".stock-history").css('display', 'none');

        $("#stock tr").click(function(){
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
                        '<td role="columnheader">'+v["inout"]+'</td>' +
                        '<td role="columnheader">'+v["weight"]+'</td>' +
                        '<td role="columnheader">'+v["description"]+'</td>' +
                        '<td role="columnheader">'+v["user_id"]+'</td>' +
                        '<td role="columnheader">'+v["updated_at"]+'</td>' +
                        '<td role="columnheader"><img style="width: 50px" src="../../storage/'+v["receipt"]+'"></td></tr>';
                });
                $(".stock-history tbody").empty();
                $(".stock-history tbody").append(toAppend);
            });
            $(".stock-history").css('display', 'inherit');
        });

        $('#edit, #delete').click(function(event){
            event.stopPropagation();
        });


    </script>

@endsection