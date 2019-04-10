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
                    <input type="text" class="form-control" name="reference" value="{{@$quotation->reference ?: $quotationId}}" readonly>
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
                    <label for="tot_weight">Peso total da meia (g):</label>
                    <input type="number" class="form-control" name="tot_weight" id="tot_weight" value="" readonly>
                </div>
                <div class="form-group col-md-4">
                    <label for="tot_weight">Peso total da meia (g) + defeito:</label>
                    <input type="number" class="form-control" id="tot_weight_plus_defect" value="" readonly>
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
                            <th role="columnheader" style="width: 12%;">Gramas</th>
                            <th role="columnheader" style="width: 9%;">Gramas + Defeito</th>
                            <th role="columnheader" style="width: 10%;">%</th>
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
                                    <input type="number" id="kgs-{{$i}}" data-index="{{$i}}" name="kgs_{{$i}}" class="form-control mats dropdown-toggle kgs"
                                           value="{{@$quotation->specs ? @$quotation->specs->toArray()[$i]['kgs'] : 0}}">
                                </td>
                                <td role="columnheader" data-col2="Kgs + Defeito">
                                    <input type="text" id="kgsPlusDefect{{$i}}" name="kgs_plus_def_{{$i}}" class="form-control dropdown-toggle kgs-plus-defect" value="0" readonly>
                                </td>
                                <td role="columnheader" data-col3="%">
                                    <input type="number" id="percentage_1_{{$i}}" data-index="{{$i}}" name="percentage_1_{{$i}}" class="percentage1 form-control mats dropdown-toggle"
                                           value="{{@$quotation->specs ? @$quotation->specs->toArray()[$i]['manual_percentage'] : 0}}">
                                </td>
                                <td role="columnheader" data-col4="Matéria-Prima">
                                    <input type="text" id="" name="sample_article_1_{{$i}}" class="tog1-{{$i}} form-control mats dropdown-toggle price"
                                           value="{{@$quotation->specs ? @$quotation->specs->toArray()[$i]['material'] : ''}}">
                                    <select style="display:none; height: 25px; padding: 0 5px;" class="form-control tog2-{{$i}} stock" id="stock{{$i}}" name="sample_article_2_{{$i}}">
                                        @foreach($warehouseProductSpecs as $spec)
                                            <option value="{{$spec->product->reference . ' - ' . $spec->color}}" data-val="{{$spec->id}}" data-index="{{$i}}">{{$spec->product->reference . ' - ' . $spec->color}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="is_custom_{{$i}}" id="is-custom-{{$i}}" value="0">
                                </td>
                                <td role="columnheader" data-col5="%">
                                    <input type="text" id="percentage_2_{{$i}}" name="percentage_2_{{$i}}" class="form-control mats dropdown-toggle" value="0" readonly>
                                </td>
                                <td role="columnheader" data-col5="Preço">
                                    <input type="number" id="price-custom-{{$i}}" class="tog1-{{$i}} form-control mats dropdown-toggle price" name="price_custom_{{$i}}" data-index="{{$i}}"
                                           value="{{@$quotation->specs ? @$quotation->specs->toArray()[$i]['price'] : 0}}">
                                    <input type="text" style="display:none" id="price-{{$i}}" class="tog2-{{$i}} form-control price" name="price_list_{{$i}}" readonly>
                                </td>
                                <td role="columnheader" data-col5="Total">
                                    <input type="number" id="total{{$i}}" name="total{{$i}}" class="form-control mats dropdown-toggle totals"
                                           value="{{@$quotation->specs ? @$quotation->specs->toArray()[$i]['total'] : 0}}" readonly>
                                </td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="photo">Foto:</label>
                        <input style="margin-bottom: 40px;" type="file" class="form-control-file" name="image_url" id="imgInp" {{@$quotation->name ? '' : 'required'}}>
                        <img id="blah" style="width: 200px;margin-bottom: 20px;" src="../../storage/{{@$quotation->product_image}}" />
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="company_cost">Custo para a Empresa (%):</label>
                    <input type="number" class="form-control" name="company_cost" id="company_cost" value="{{@$quotation->company_cost_percentage ?: '0'}}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="comission">Comissão (%):</label>
                    <input type="number" class="form-control" name="comission" id="comission" value="{{@$quotation->comission_percentage ?: '0'}}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="transportation">Transporte (%):</label>
                    <input type="number" class="form-control" name="transportation" id="transportation" value="{{@$quotation->transportation_percentage ?: '0'}}" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="extra1">Extra 1 (%):</label>
                    <input type="number" class="form-control" name="extra1" id="extra1" value="{{@$quotation->extra_percentage ?: '0'}}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="extra2">Extra 2 (%):</label>
                    <input type="number" class="form-control" name="extra2" id="extra2" value="{{@$quotation->extra_2_percentage ?: '0'}}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="total">Total:</label>
                    <input type="number" class="form-control" name="total" id="total" value="0" readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="client_price">Preço para Cliente:</label>
                    <input type="number" class="form-control" name="client_price" value="{{@$quotation->client_price ?: '0'}}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6" style="margin-top:60px;margin-bottom:40px;">
                    <button type="submit" style="float:left" class="btn btn-success col-md-5">{{@$quotation->id ? 'Atualizar Orçamento' : 'Criar Orçamento'}}</button>
                    <button type="button" style="float:right" onclick="window.history.back();" class="btn btn-info col-md-5">Voltar</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $( document ).ready( function () {
            for(let i = 0; i < 12; i++) {
                let value = $("#kgs-"+i).val();
                calcKgsPlusPercentageRow(i, value);
            }
            addKgs();
            calcTotal();
            calcAllPercentage2();
        });

        //Mudar origem das matérias primas (sinal de +)
        $(".change_origin").click( function () {
            let index = this.id;
            let id = $("#stock"+index).find(':selected').data('val');
            let custom = 0;
            $(".tog1-"+index).toggle();
            $(".tog2-"+index).toggle();
            if($(".tog2-"+index).css('display') !== 'none') {
                custom = 1;
                $("#is-custom-"+index).val(1);
            } else {
                $("#is-custom-"+index).val(0);
            }
            setPrice(id, index, custom);
        });

        //Mudar de Matéria prima no select
        $(".stock").change( function () {
            let id = $(this).find(':selected').data('val');
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
            calcAllPercentage2();
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

        $(".percentage1, .kgs").on('change click', function () {
            calcAllPercentage2();
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
            let rowTotal = Number(total) * Number(kgsPlusDefect) / 1000;
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

            //Valores sem percentage
            let totalOutrosValoresSemPercentage = 0;
            $(".kgs").each( function (k,v) {
                console.log(k);
                let price = $("#price-custom-" + k).val();
                let totalPrice = $("#total" + k).val();
                if (price !== '0' && totalPrice === '0.00') {
                    totalOutrosValoresSemPercentage += parseInt(price);
                }
            });
            total = total + total * (companyCost + comission + transportation + extra1 + extra2) / 100 + parseInt(totalOutrosValoresSemPercentage);
            $("#total").val(total.toFixed(2));
        }

        function calcAllPercentage2() {
            $(".percentage1").each( function (k, v) {
                let kgsWithDefect = $("#kgsPlusDefect"+k).val();
                let totWithDefect = $("#tot_weight_plus_defect").val();
                $("#percentage_2_"+k).val((kgsWithDefect * 100 /  totWithDefect).toFixed(2));
            });
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