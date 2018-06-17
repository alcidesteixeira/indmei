@extends('layouts.app')

@section('content')

    <div class="container">
        @include('flash::message')


        <h2>Stock de produtos disponíveis</h2>
        <table class="table table-striped thead-dark" id="stock">
            <thead>
            <tr>
                <th>Referência</th>
                <th>Cor</th>
                <th>Stock Bruto (g)</th>
                <th>Stock Líquido (g)</th>
                <th>Atualizado Por</th>
                <th>Descrição</th>
                <th>Alerta mínimo (g)</th>
                <th>Última Atualização</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($stock as $product)
                <tr style="background-color: {{$product->threshold >= $product->weight ? '#f9a9a9' : ''}}" data-specid="{{$product->id}}">
                    <td>{{$product->product->reference}}</td>
                    <td>{{$product->color}}</td>
                    <td>{{$product->weight}}</td>
                    <td>{{$product->weight}}</td>
                    <td>{{$product->product->user->name}}</td>
                    <td>{{$product->description}}</td>
                    <td>{{$product->threshold}}</td>
                    <td>{{$product->updated_at}}</td>
                    <td>
                        <form method="get" action="{{url('stock/edit/'.$product->id)}}" id="edit" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>
                    </td>
                    <td>
                        <button type="button" data-id="{{$product->id}}" data-role="{{$product->product->reference}}" id="delete" class="apagarform btn btn-danger">Apagar</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="container stock-history">
        <h4>Histórico de Matéria-Prima</h4>
        <table class="table table-striped thead-dark">
            <thead>
            <tr>
                <th>Entrada/Saída</th>
                <th>Quantidade (g)</th>
                <th>Descrição</th>
                <th>Atualizado Por</th>
                <th>Última Atualização</th>
                <th>Fatura</th>
            </tr>
            </thead>
            <tbody>
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

        //Select table row from stock to show details
        $(".stock-history").css('display', 'none');

        $("#stock tr").click(function(){
            $(this).addClass('selected').siblings().removeClass('selected');
            let value=$(this).data('specid');
            alert(value);
            $.ajax({
                url: "/stock/list/historic/"+value,
            }).done(function(data) {
                console.log(data);
                let toAppend = '';
                $.each(data, function(k,v) {
                    toAppend = toAppend + '<tr><td>'+v["inout"]+'</td><td>'+v["weight"]+'</td><td>'+v["description"]+'</td>' +
                        '<td>'+v["user_id"]+'</td><td>'+v["updated_at"]+'</td><td><img src="'+v["receipt"]+'"></td></tr>';
                });
                $(".stock-history tbody").append('');
                $(".stock-history tbody").append(toAppend);
            });
            $(".stock-history").css('display', 'inherit');
        });

        $('#edit, #delete').click(function(event){
            event.stopPropagation();
        });


    </script>

@endsection