@extends('layouts.app')

@section('content')
    <style>
        button a, a:hover {
            color: #000;
            text-decoration: none;
        }
        button a:hover {
            color: #fff;
            text-decoration: none;
        }
        .table td, .table th {
            padding: 0.3em;
        }
        td .form-control {
            height: 25px;
        }
        td .btn-info {
             height: 25px;
            padding: 0 5px;
         }
    </style>

    <div class="container">
        <h2>{{@$quotation->id ? 'Atualizar Orçamento' : 'Criar Orçamento'}}</h2><br/>
        <form method="post" action="{{@$quotation->id ? url('quotation/update/'.$quotation->id) : url('quotation/create')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="reference">Referência:</label>
                    <input type="text" class="form-control" name="reference" value="{{@$quotation->reference ?: $quotationId}}" required readonly>
                </div>
                <div class="form-group col-md-4">
                    <label for="client">Cliente:</label>
                    <select class="form-control" name="client">
                        @foreach($clients as $client)
                            <option value="{{$client->id}}" {{@$quotation->client == $client->id ? 'selected' : ''}}>{{$client->client}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="date">Data:</label>
                    <input type="date" class="form-control" name="date" value="{{@$quotation->date}}" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="tot_weight">Peso total da meia:</label>
                    <input type="text" class="form-control" name="tot_weight" value="{{@$total_weight}}" required readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="defect">Defeito:</label>
                    <input type="text" class="form-control" name="defect" value="{{@$quotation->defect_percentage ?: 7}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <table class="table table-striped thead-dark" role="table">
                        <thead role="rowgroup">
                        <tr role="row">
                            <th role="columnheader">Origem</th>
                            <th role="columnheader" style="width: 8%;">Kgs</th>
                            <th role="columnheader" style="width: 8%;">Kgs + Defeito</th>
                            <th role="columnheader" style="width: 8%;">%</th>
                            <th role="columnheader">Matéria-Prima</th>
                            <th role="columnheader" style="width: 8%;">%</th>
                            <th role="columnheader" style="width: 15%;">Preço</th>
                            <th role="columnheader" style="width: 15%;">Total</th>
                        </tr>
                        </thead>
                        <tbody role="rowgroup">
                        @for($i=0;$i<12;$i++)
                            <tr role="row">
                                <td role="columnheader" data-col1="Orgigem">
                                    <input type="button" id="{{$i}}" class="change_origin btn btn-info" value="+">
                                </td>
                                <td role="columnheader" data-col1="Kgs">
                                    <input type="text" id="kgs-{{$i}}" name="kgs-{{$i}}" class="form-control mats dropdown-toggle" value="">
                                </td>
                                <td role="columnheader" data-col2="Kgs + Defeito">

                                </td>
                                <td role="columnheader" data-col3="%">
                                    <input type="text" id="" class="form-control mats dropdown-toggle" value="">
                                </td>
                                <td role="columnheader" data-col4="Matéria-Prima">
                                    <input type="text" id="" class="tog1-{{$i}} form-control mats dropdown-toggle" value="">
                                    <select style="display:none; height: 25px; padding: 0 5px;" class="form-control tog2-{{$i}}" id="" name="client">
                                        @foreach($warehouseProductSpecs as $spec)
                                            <option value="{{$spec->product->reference . ' - ' . $spec->color}}">{{$spec->product->reference . ' - ' . $spec->color}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td role="columnheader" data-col5="%"><input type="text" id="" class="form-control mats dropdown-toggle" value=""></td>
                                <td role="columnheader" data-col5="Preço">
                                    <input type="text" id="" class="tog1-{{$i}} form-control mats dropdown-toggle" value="">
                                </td>
                                <td role="columnheader" data-col5="Total">
                                    <input type="text" id="" class="form-control mats dropdown-toggle" value="">
                                </td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="company_cost">Foto:</label>
                        <input type="file" name="photo" value="">
                        <img src="" alt="" style="width:100%;">
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="company_cost">Custo para a Empresa:</label>
                    <input type="number" class="form-control" name="company_cost" value="" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="comission">Comissão:</label>
                    <input type="number" class="form-control" name="comission" value="" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="transportation">Transporte:</label>
                    <input type="number" class="form-control" name="transportation" value="" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="extra1">Extra 1:</label>
                    <input type="number" class="form-control" name="extra1" value="" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="extra2">Extra 2:</label>
                    <input type="number" class="form-control" name="extra2" value="" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="total">Total:</label>
                    <input type="number" class="form-control" name="total" value="" required readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="client_price">Preço para Cliente:</label>
                    <input type="number" class="form-control" name="client_price" value="" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6" style="margin-top:60px;margin-bottom:40px;">
                    <button type="submit" style="float:left" class="btn btn-success col-md-5">{{@$quotation->id ? 'Atualizar e ir para Enviar Email' : 'Criar e ir para Enviar Email'}}</button>
                    <button type="button" style="float:right" onclick="window.history.back();" class="btn btn-info col-md-5">Voltar</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $( document ).ready( function () {

        });

        $(".change_origin").click( function () {
            let id = this.id;
            $(".tog1-"+id).toggle();
            $(".tog2-"+id).toggle();
        });
    </script>
@endsection