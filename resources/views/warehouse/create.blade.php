@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h2>{{@$stock->product->reference ? 'Atualizar Matéria-Prima' : 'Criar Nova Matéria-Prima'}}</h2><br/>
        </div>
        <div class="row">
            <div class="col-md-6">
                <form method="post" action="{{@$stock->product->reference ? url('stock/update/'.$stock->id) : url('stock/create')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="form-group col-md-8">
                            <label for="Description">Referência Fornecedor:</label>
                            <input type="text" class="form-control" name="description" value="{{@$stock->description}}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="form-group col-md-8">
                            <label for="Reference">Referência INDMEI:</label>
                            <input type="text" class="form-control" name="reference" value="{{@$stock->product->reference}}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="form-group col-md-8">
                            <label for="color">Cor:</label>
                            <input type="text" class="form-control" name="color" value="{{@$stock->color}}" required>
                        </div>
                    </div>

                    @if(@$stock->product->reference)
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="form-group col-md-8">
                                <label for="weight">Peso Bruto (Kg):</label>
                                <input type="text" class="form-control" name="gross_weight" value="{{@$stock->gross_weight/1000}}" required {{@$stock->product->reference ? 'readonly' : ''}}>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="form-group col-md-8">
                            <label for="weight">Peso (Kg):</label>
                            <input type="text" class="form-control" name="liquid_weight" value="{{@$stock->liquid_weight/1000}}" required {{@$stock->product->reference ? 'readonly' : ''}}>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="form-group col-md-8">
                            <label for="threshold">Valor de aviso mínimo (Kg):</label>
                            <input type="number" step="0.01" class="form-control" name="threshold" value="{{@$stock->threshold}}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="form-group col-md-8">
                            <label for="cost">Custo por Kg:</label>
                            <input type="number" step="0.01" class="form-control" id="cost" name="cost" value="{{@$stock->cost}}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="submit-buttons form-group col-md-8" style="margin-top:60px">
                            <button type="submit" class="btn btn-success">{{@$stock->product->reference ? 'Atualizar' : 'Criar'}}</button>
                            <button type="button" onclick="window.history.back();" class="btn btn-info">Voltar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6" style="border:1px solid grey; height: 100%">
                @include('warehouse.receipt_simple_include')
            </div>

        </div>
    </div>


    <script>
    </script>

@endsection
