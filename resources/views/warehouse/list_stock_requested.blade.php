@extends('layouts.app')

@section('content')
    <div class="container">
        @include('flash::message')

        <h2>Histórico de pedidos de Stock</h2>
        <hr>
        @foreach($stockRequested as $stock)
            <?php
            $sender = explode(';', $stock->email_sent)[0];

            $start = strpos($stock->email_sent, ';');

            $email = substr($stock->email_sent . ' ', $start + 9, -1);
            ?>
        <div class="row">
            <div class="col-sm-6">
                <p style="font-weight: bold;">Data de envio: <span style="font-weight: normal;">{{$stock->created_at}}</span></p>
                <p style="font-weight: bold;">{{ucfirst(explode(': ', $sender)[0])}}: <span style="font-weight: normal;">{{explode(': ', $sender)[1]}}</span></p>
                <p style="font-weight: bold;">Quantidade solicitada: <span style="font-weight: normal;">{{$stock->amount_requested}} Kg</span></p>
            </div>
            <div class="col-sm-6">
                <p style="font-weight: bold;">Email: <span style="font-weight: normal;">{!! $email !!}</span></p>
            </div>
        </div>
        <hr>
        @endforeach
    </div>

@endsection