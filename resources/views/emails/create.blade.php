@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Criar novo Email</div>

                    <div class="card-body">
                        <form method="post" action="{{url('email/send/')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="sel1">Enviar para</label>
                                <select class="form-control" id="client" name="client">
                                    <option value="0">Novo</option>
                                    @foreach($clients as $client)
                                    <option value="{{$client->email}}">{{$client->client}} ({{$client->email}})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="usr">Novo Endereço de envio:</label>
                                <input type="email" class="form-control" id="new_address" name="new_address">
                            </div>
                            <div class="form-group">
                                <label for="usr">Assunto:</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            <div class="form-group">
                                <label for="comment">Conteúdo:</label>
                                <textarea class="form-control" rows="5" id="body" name="body" required></textarea>
                            </div>
                            <input type="hidden" name="body2" id="body2">
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="form-group col-md-6" style="margin-top:20px;margin-bottom:20px;">
                                    <button type="submit" style="float:left" class="btn btn-success col-md-5">Enviar</button>
                                    <button type="button" style="float:right" onclick="window.history.back();" class="btn btn-info col-md-5">Voltar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $( document ).ready( function () {
            $("form").submit ( function (e) {
                let client = $("#client").val();
                let newAddress = $("#new_address").val();
                if(client == 0 && newAddress == '') {
                    alert("Deve selecionar pelo menos um endereço de envio de email.")
                    e.preventDefault();
                }
            });

            //Before submit get html code of textarea
            $("form").submit( function () {
                $("#body2").val($("#body").val().replace(/\n|\r/g,'<br />'));
            });
        });
    </script>
@endsection