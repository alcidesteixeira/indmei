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
                    <input type="text" class="form-control" name="reference" value="{{@$quotation->reference ?: $quotationId}}" readonly disabled>
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
                <div class="form-group col-md-4">
                    <label for="tot_weight">Peso total da meia:</label>
                    <input type="number" class="form-control" name="tot_weight" id="tot_weight" value="{{@$total_weight}}" readonly disabled>
                </div>
                <div class="form-group col-md-4">
                    <label for="tot_weight">Peso total da meia + defeito:</label>
                    <input type="number" class="form-control" id="tot_weight_plus_defect" value="{{@$total_weight}}" readonly disabled>
                </div>
                <div class="form-group col-md-4">
                    <label for="defect">Defeito (%):</label>
                    <input type="number" class="form-control" name="defect" id="defect" value="{{@$quotation->defect_percentage ?: 7}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <table class="table table-striped thead-dark" role="table">
                        <thead role="rowgroup">
                        <tr role="row">
                            <th role="columnheader">Origem</th>
                            <th role="columnheader" style="width: 12%;">Kgs</th>
                            <th role="columnheader" style="width: 9%;">Kgs + Defeito</th>
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
                                    <input type="number" id="kgs-{{$i}}" data-index="{{$i}}" name="kgs-{{$i}}" class="form-control mats dropdown-toggle kgs" value="0">
                                </td>
                                <td role="columnheader" data-col2="Kgs + Defeito">
                                    <input type="text" id="kgsPlusDefect{{$i}}" class="form-control dropdown-toggle kgs-plus-defect" value="0" disabled>
                                </td>
                                <td role="columnheader" data-col3="%">
                                    <input type="text" id="" class="form-control mats dropdown-toggle" value="0">
                                </td>
                                <td role="columnheader" data-col4="Matéria-Prima">
                                    <input type="text" id="" class="tog1-{{$i}} form-control mats dropdown-toggle price" value="">
                                    <select style="display:none; height: 25px; padding: 0 5px;" class="form-control tog2-{{$i}} stock" id="stock{{$i}}" name="client">
                                        @foreach($warehouseProductSpecs as $spec)
                                            <option value="{{$spec->id}}" data-index="{{$i}}">{{$spec->product->reference . ' - ' . $spec->color}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td role="columnheader" data-col5="%">
                                    <input type="text" id="" class="form-control mats dropdown-toggle" value="0">
                                </td>
                                <td role="columnheader" data-col5="Preço">
                                    <input type="number" id="price-custom-{{$i}}" class="tog1-{{$i}} form-control mats dropdown-toggle price" name="price-custom-{{$i}}" data-index="{{$i}}" value="0">
                                    <input type="text" style="display:none" id="price-{{$i}}" class="tog2-{{$i}} form-control price" name="price-list-{{$i}}" disabled>
                                </td>
                                <td role="columnheader" data-col5="Total">
                                    <input type="number" id="total{{$i}}" class="form-control mats dropdown-toggle totals" value="0.00" disabled>
                                </td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="photo">Foto:</label>
                        <input style="margin-bottom: 40px;" type="file" class="form-control-file" name="image_url" id="imgInp" {{@$sampleArticle->reference ? '' : 'required'}}>
                        <img id="blah" style="width: 200px;margin-bottom: 20px;" src="../../storage/{{@$sampleArticle->image_url}}" />
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="company_cost">Custo para a Empresa (%):</label>
                    <input type="number" class="form-control" name="company_cost" id="company_cost" value="0" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="comission">Comissão (%):</label>
                    <input type="number" class="form-control" name="comission" id="comission" value="0" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="transportation">Transporte (%):</label>
                    <input type="number" class="form-control" name="transportation" id="transportation" value="0" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="extra1">Extra 1 (%):</label>
                    <input type="number" class="form-control" name="extra1" id="extra1" value="0" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="extra2">Extra 2 (%):</label>
                    <input type="number" class="form-control" name="extra2" id="extra2" value="0" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="total">Total:</label>
                    <input type="number" class="form-control" name="total" id="total" value="0" readonly disabled>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="client_price">Preço para Cliente:</label>
                    <input type="number" class="form-control" name="client_price" value="0" required>
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

        //Mudar origem das matérias primas (sinal de +)
        $(".change_origin").click( function () {
            let index = this.id;
            let id = $("#stock"+index).val();
            let custom = 0;
            $(".tog1-"+index).toggle();
            $(".tog2-"+index).toggle();
            if($(".tog2-"+index).css('display') !== 'none') {
                custom = 1;
            }
            setPrice(id, index, custom);
        });

        //Mudar de Matéria prima no select
        $(".stock").change( function () {
            let id = $(this).find(':selected').val();
            let index = $(this).find(':selected').data('index');
            setPrice(id, index);
            let custom = 0;
            if($(".tog2-"+index).css('display') !== 'none') {
                custom = 1;
            }
            setPrice(id, index, custom);
            calcTotal();
        });

        //Mudar valor de Kgs
        $(".kgs").on('change click', function () {
            addKgs();
            let index = $(this).data('index');
            let value = $(this).val();
            calcKgsPlusPercentageRow(index, value);
            //Obter Preço atual da propriedade visivel
            if($("#price-"+index).css('display') == 'none') {
                let price = $("#price-custom-"+index).val();
                calcTotalPerRow(index, price);
            } else {
                let price = $("#price-"+index).val();
                calcTotalPerRow(index, price);
            }
            calcTotal();
        });

        $("#defect").on ('change click', function () {
            for (let i = 0; i < 12; i++) {
                let index = i;
                let value = $("#kgs-"+i).val();
                calcKgsPlusPercentageRow(index, value);
                addKgs();
                //Obter Preço atual da propriedade visivel
                if($("#price-"+index).css('display') == 'none') {
                    let price = $("#price-custom-"+index).val();
                    calcTotalPerRow(index, price);
                } else {
                    let price = $("#price-"+index).val();
                    calcTotalPerRow(index, price);
                }
                calcTotal();
            }
        });

        $(".price").on('change click', function () {
            let index = $(this).data('index');
            let total = $(this).val();
            calcTotalPerRow(index, total);
            calcTotal();
        });

        $("#company_cost, #comission, #transportation, #extra1, #extra2").on("change click", function () {
            calcTotal();
        });

        function setPrice(id, index, custom = 0) {
            $.ajax({
                url: "/quotation/price/update/"+id,
            }).done(function(data) {
                $('#price-'+index).val(data);
                //Usar valor customizado no cálculo, e não o do armazém
                if (custom === 1) {
                    calcTotalPerRow(index, data);
                } else {
                    let total = $("#price-custom-"+index).val();
                    calcTotalPerRow(index, total);
                }
                calcTotal();
            });
        }

        function addKgs() {
            let kg = 0;
            let kg_and_defect;
            let defect = $("#defect").val();
            $(".kgs").each( function () {
                kg += Number($(this).val());
            });
            kg_and_defect = kg + kg * defect / 100;
            $("#tot_weight").val(kg);
            $("#tot_weight_plus_defect").val(kg_and_defect);
        }

        function calcKgsPlusPercentageRow(index, value) {
            let defect = $("#defect").val();
            let valAfterPercentage = Number(value) + Number(value) * defect / 100;
            $("#kgsPlusDefect"+index).val(valAfterPercentage);
        }

        function calcTotalPerRow(index, total) {
            let kgsPlusDefect = $("#kgsPlusDefect"+index).val();
            let rowTotal = Number(total) * Number(kgsPlusDefect);
            $("#total"+index).val(rowTotal.toFixed(2));
        }

        function calcTotal() {
            let total = 0;
            let companyCost = Number($("#company_cost").val());
            let comission = Number($("#comission").val());
            let transportation = Number($("#transportation").val());
            let extra1 = Number($("#extra1").val());
            let extra2 = Number($("#extra2").val());
            $(".totals").each( function () {
                total += Number($(this).val());
            });
            total = total + total * (companyCost + comission + transportation + extra1 + extra2) / 100;
            $("#total").val(total.toFixed(2));
        }

        //Preview image before upload
        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imgInp").change(function() {
            readURL(this);
            $("#blah").css('display', 'block');
        });
    </script>
@endsection