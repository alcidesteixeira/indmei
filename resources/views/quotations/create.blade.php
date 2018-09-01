@extends('layouts.app')

@section('content')
    <style>
        button a, a:hover {
            color: #fff;
            text-decoration: none;
        }
    </style>


    <div class="container">
        <h2>{{@$quotation->id ? 'Atualizar Orçamentação' : 'Criar Orçamentação'}}</h2><br/>
        <form method="post" action="{{@$quotation->id ? url('quotation/update/'.$quotation->id) : url('quotation/create')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="Status">Status:</label>
                    <select class="form-control" name="status_id" readonly disabled>
                        @foreach($statuses as $status)
                            <option value="{{$status->id}}" {{$status->id == @$order->status_id ? 'selected' : ''}}>{{$status->status}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="client_id">Nome do Cliente:</label>
                    <input type="text" class="form-control" value="{{@$order->client->client}}" required readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="client_identifier">Identificador do Cliente:</label>
                    <input type="text" class="form-control" value="{{@$order->client_identifier}}" required readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="Description">Descrição:</label>
                    <input type="text" class="form-control" value="{{@$order->description}}" required readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="delivery_date">Data de entrega:</label>
                    <input type="date" class="form-control" value="{{@$order->delivery_date}}" required readonly>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-6">
                    <label style="padding-right: 20px;">Identificador INDMEI:</label>
                        <button type="button" class="btn btn-info"><a href="{{url('/samples/edit/'.@$order->sample_article_id)}}" target="_blank">Ver Amostra de Artigo</a></button>
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-md-2"></div>
                <label class="col-md-2 col-form-label" for="orderCost">Amostra de Cor 1:</label>
                <div class="input-group col-md-2">
                <input type="number" step="0.01" class="form-control" id="order_cost1" name="order_cost1" value="{{@$quotation->order_sample_cost_1 ? $quotation->order_sample_cost_1 : @$order->sampleArticle->cost1}}" required>
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                </div>
                <label class="col-md-2 col-form-label" for="orderCost">Total Pares:</label>
                <div class="input-group col-md-2">
                    <input type="number" step="0.01" class="form-control" id="color1" name="color1" value="{{@$order->tamanho11 + @$order->tamanh12 + @$order->tamanho13 + @$order->tamanho14}}" required readonly>
                    <div class="input-group-prepend">
                        <div class="input-group-text">Total</div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2"></div>
                <label class="col-md-2 col-form-label" for="orderCost">Amostra de Cor 2:</label>
                <div class="input-group col-md-2">
                    <input type="number" step="0.01" class="form-control" id="order_cost2" name="order_cost2" value="{{@$quotation->order_sample_cost_2 ? $quotation->order_sample_cost_2 : @$order->sampleArticle->cost2}}" required>
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                </div>
                <label class="col-md-2 col-form-label" for="orderCost">Total Pares:</label>
                <div class="input-group col-md-2">
                    <input type="number" step="0.01" class="form-control" id="color2" name="color2" value="{{@$order->tamanho21 + @$order->tamanho22 + @$order->tamanho23 + @$order->tamanho24}}" required readonly>
                    <div class="input-group-prepend">
                        <div class="input-group-text">Total</div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2"></div>
                <label class="col-md-2 col-form-label" for="orderCost">Amostra de Cor 3:</label>
                <div class="input-group col-md-2">
                    <input type="number" step="0.01" class="form-control" id="order_cost3" name="order_cost3" value="{{@$quotation->order_sample_cost_3 ? $quotation->order_sample_cost_3 : @$order->sampleArticle->cost3}}" required>
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                </div>
                <label class="col-md-2 col-form-label" for="orderCost">Total Pares:</label>
                <div class="input-group col-md-2">
                    <input type="number" step="0.01" class="form-control" id="color3" name="color3" value="{{@$order->tamanho31 + @$order->tamanho32 + @$order->tamanho33 + @$order->tamanho34}}" required readonly>
                    <div class="input-group-prepend">
                        <div class="input-group-text">Total</div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2"></div>
                <label class="col-md-2 col-form-label" for="orderCost">Amostra de Cor 4:</label>
                <div class="input-group col-md-2">
                    <input type="number" step="0.01" class="form-control" id="order_cost4" name="order_cost4" value="{{@$quotation->order_sample_cost_4 ? $quotation->order_sample_cost_4 : @$order->sampleArticle->cost4}}" required>
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                </div>
                <label class="col-md-2 col-form-label" for="orderCost">Total Pares:</label>
                <div class="input-group col-md-2">
                    <input type="number" step="0.01" class="form-control" id="color4" name="color4" value="{{@$order->tamanho41 + @$order->tamanho42 + @$order->tamanho43 + @$order->tamanho44}}" required readonly>
                    <div class="input-group-prepend">
                        <div class="input-group-text">Total</div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2"></div>
                <label class="col-md-2 col-form-label" for="tag">Custo das etiquetas:</label>
                <div class="input-group col-md-6">
                    <input type="number" step="0.01" class="form-control" id="tag" name="tag" value="{{@$quotation->tags ? $quotation->tags : 0}}" required>
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2"></div>
                <label class="col-md-2 col-form-label" for="boxes">Custo das Caixas:</label>
                <div class="input-group col-md-6">
                    <input type="number" step="0.01" class="form-control" id="boxes" name="boxes" value="{{@$quotation->boxes ? $quotation->boxes : 0}}" required>
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2"></div>
                <label class="col-md-2 col-form-label" for="defect">Peças com defeito:</label>
                <div class="input-group col-md-6">
                    <input type="number" step="0.01" class="form-control" id="defect" min="0" max="100" name="defect" value="{{@$quotation->defect ? $quotation->defect : 0}}" required>
                    <div class="input-group-prepend">
                        <div class="input-group-text">%</div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2"></div>
                <label class="col-md-2 col-form-label" for="manpower">Mão-de-Obra:</label>
                <div class="input-group col-md-6">
                    <input type="number" step="0.01" class="form-control" id="manpower" name="manpower" value="{{@$quotation->manpower ? $quotation->manpower : 0}}" required>
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2"></div>
                <label class="col-md-2 col-form-label" for="otherCosts">Outros Custos:</label>
                <div class="input-group col-md-6">
                    <input type="number" step="0.01" class="form-control" id="other_costs" name="other_costs" value="{{@$quotation->other_costs ? $quotation->other_costs : 0}}" required>
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2"></div>
                <label class="col-md-2 col-form-label" for="total">Total:</label>
                <div class="input-group col-md-6">
                    <input type="number" step="0.01" class="form-control" id="totalVal" value="0" required readonly>
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2"></div>
                <label class="col-md-2 col-form-label" for="totalSent">Total a Enviar:</label>
                <div class="input-group col-md-6">
                    <input type="number" step="0.01" class="form-control" name="total_sent" value="{{@$quotation->value_sent ? $quotation->value_sent : 0}}" required>
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                </div>
            </div>
            <input type="hidden" class="form-control" name="order_id" value="{{@$order->id}}" required>
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

            calcTotalVal ();

            $( "input" ).bind("keyup change", function() {
                calcTotalVal ();

            });
        });

        function calcTotalVal () {
            let totalSamples = parseFloat($("#color1").val()) + parseFloat($("#color2").val()) + parseFloat($("#color3").val()) + parseFloat($("#color4").val());
            let total = (
                (parseFloat($("#order_cost1").val()) * parseFloat($("#color1").val())) +
                (parseFloat($("#order_cost2").val()) * parseFloat($("#color2").val())) +
                (parseFloat($("#order_cost3").val()) * parseFloat($("#color3").val())) +
                (parseFloat($("#order_cost4").val()) * parseFloat($("#color4").val())) +
                parseFloat($("#tag").val()) +
                parseFloat($("#boxes").val()) +
                (totalSamples * parseFloat($("#defect").val()) / 100) +
                parseFloat($("#manpower").val()) +
                parseFloat($("#other_costs").val())).toFixed(2);

            $("#totalVal").val(total);
        }
    </script>
@endsection