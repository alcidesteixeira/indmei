@extends('layouts.app')

@section('content')

<link href="{{ asset('css/stats.css') }}" rel="stylesheet">

<div class="container" style="margin-bottom: 50px;">

    <form method="POST" id="form" action="{{ url('/stats/update') }}">
        @csrf
        <div class="row">
            <div class="form-group col-md-6">
                <label for="delivery_date">Data de início:</label>
                <input type="date" class="form-control" name="start_date" value="{{$start_date}}" required>
            </div>
            <div class="form-group col-md-6">
                <label for="delivery_date">Data de fim:</label>
                <input type="date" class="form-control" name="end_date" value="{{$end_date}}" required>
            </div>
            <div class="form-group col-md-12">
                <label for="delivery_date">Tipo de filtro:</label>
                <input type="button" class="btn {{$filter == 'Dia' ? 'btn-info' : ''}} form-control col-sm-2" name="daily" value="Dia">
                <input type="button" class="btn {{$filter == 'Mês' ? 'btn-info' : ''}} form-control col-sm-2" name="monthly" value="Mês">
                <input type="button" class="btn {{$filter == 'Ano' ? 'btn-info' : ''}} form-control col-sm-2" name="yearly" value="Ano">
                <input type="hidden" id="filter" name="filter">
                <button type="submit" class="btn btn-primary" style="float:right">
                    Submeter
                </button>
            </div>
        </div>

    </form>


    <div class="row" style="margin-bottom: 30px;">
        <div class="col-md-6">
            <h4>Quantidades produzidas de meias</h4>
            <canvas id="myChart1" style="height: 370px; width: 100%;"></canvas>
        </div>
        <div class="col-md-6">
            <h4>Matéria-Prima gasta, por fio</h4>
            <canvas id="myChart2" style="height: 370px; width: 100%;"></canvas>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h4>Clientes com mais pares produzidos</h4>
            <canvas id="myChart3" style="height: 370px; width: 100%;"></canvas>
        </div>
        <div class="col-md-6">
            <h4>Receita total de encomenda por Cliente</h4>
            <canvas id="myChart4" style="height: 370px; width: 100%;"></canvas>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>


<script>
    $(".btn").click( function () {
        $(".btn").removeClass("btn-info");
        $( this ).addClass("btn-info");
    });

    $(document).ready(function () {
        //Verificar pelo que é feito o Filtro
        let filter = $("input:button").val();

        $("input:button").click( function () {
           filter = $(this).val();

        });

        $("#form").submit( function () {
            $("#filter").val(filter);
        });
    });



</script>

<script>

    //Obter os Resultados do Gráfico 1
    let socksData = {!! json_encode($socks) !!};
    let socks = [];
    // console.log(socksData);

    //Obter a Label
    let labelArr = {!! json_encode($label) !!};
    let label = [];
    $.each(labelArr, function (k, v) {
        label.push(v);
        if(socksData[v]) {
            socks.push(socksData[v]);
        } else {
            socks.push(0);
        }
    });
    // console.log(label);
    // console.log(socks);


    //Gráfico 1
    var ctx1 = document.getElementById("myChart1").getContext('2d');
    var myChart1 = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: label,
            datasets: [{
                data: socks,
                label: "Meias Produzidas",
                borderColor: "#8e5ea2",
                fill: false
            }]
        },
        options: {
            title: {
                display: false,
                text: 'World population per region (in millions)'
            }
        }
    });

    //Gráfico 2
    var ctx2 = document.getElementById("myChart2").getContext('2d');
    var myChart2 = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
            datasets: [{
                label: "Population (millions)",
                backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                data: [2478,5267,734,784,433]
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Predicted world population (millions) in 2050'
            }
        }
    });

    //Gráfico 3
    var ctx3 = document.getElementById("myChart3").getContext('2d');
    var myChart13 = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: ["1900", "1950", "1999", "2050"],
            datasets: [
                {
                    label: "Africa",
                    backgroundColor: "#3e95cd",
                    data: [133,221,783,2478]
                }
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Population growth (millions)'
            }
        }
    });

    //Gráfico 4
    var ctx4 = document.getElementById("myChart4").getContext('2d');
    var myChart4 = new Chart(ctx4, {
        type: 'line',
        data: {
            labels: [1500,1600,1700,1750,1800,1850,1900,1950,1999,2050],
            datasets: [{
                data: [86,114,106,106,107,111,133,221,783,2478],
                label: "Africa",
                borderColor: "#3e95cd",
                fill: false
            }, {
                data: [282,350,411,502,635,809,947,1402,3700,5267],
                label: "Asia",
                borderColor: "#8e5ea2",
                fill: false
            }, {
                data: [168,170,178,190,203,276,408,547,675,734],
                label: "Europe",
                borderColor: "#3cba9f",
                fill: false
            }, {
                data: [40,20,10,16,24,38,74,167,508,784],
                label: "Latin America",
                borderColor: "#e8c3b9",
                fill: false
            }, {
                data: [6,3,2,2,7,26,82,172,312,433],
                label: "North America",
                borderColor: "#c45850",
                fill: false
            }
            ]
        },
        options: {
            title: {
                display: false,
                text: 'World population per region (in millions)'
            }
        }
    });
</script>

@endsection