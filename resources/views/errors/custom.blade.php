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
            <h2 style="text-align:center;">Foi encontrado um erro inesperado na plataforma.</h2><br>
            <h4>Erro:</h4>
            <p style="font-weight: bold;">{{$message}}</p>
            <br>
            <p>Foi enviado um email para <a href="mailto:alcides.mn.teixeira@gmail.com">alcides.mn.teixeira@gmail.com</a> com o relatório do erro sucedido.</p>
            <p>Poderá enviar um print screen deste ecrã para o mesmo email, no caso considerar o erro crítico.</p>
            <p>Obrigado!</p>
            <form action="/">
                <button type="submit" class="btn btn-default">VOLTAR</button>
            </form>
        </div>
    </div>



@endsection