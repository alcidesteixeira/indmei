@extends('layouts.app')

@section('content')

    <style>
        div.ext-box { display: table; width:100%;}
        div.int-box { text-align: center;
            align-items: center;
            vertical-align: middle; }
    </style>

    <div class="ext-box">
        <div class="int-box">
            <h2 style="text-align:center;">Tem de fazer login na aplicação para poder avançar. Por favor clique no botão abaixo para proceder a esta operação.</h2>
            <form action="/">
                <button type="submit" class="btn btn-default">AQUI</button>
            </form>
        </div>
    </div>



@endsection