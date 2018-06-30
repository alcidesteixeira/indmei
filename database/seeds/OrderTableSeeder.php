<?php

use App\Order;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order = new Order();
        $order->sample_article_id = '1';
        $order->client_id = '1';
        $order->client_identifier = '1';
        $order->delivery_date = Carbon::now();
        $order->description = 'asdf';
        $order->cor1 = 'roxo';
        $order->cor2 = 'preto';
        $order->cor3 = 'rosa';
        $order->cor4 = 'verde';
        $order->tamanho11 = '300';
        $order->tamanho12 = '500';
        $order->tamanho13 = '700';
        $order->tamanho14 = '20';
        $order->tamanho21 = '300';
        $order->tamanho22 = '500';
        $order->tamanho23 = '700';
        $order->tamanho24 = '20';
        $order->tamanho31 = '300';
        $order->tamanho32 = '500';
        $order->tamanho33 = '700';
        $order->tamanho34 = '20';
        $order->tamanho41 = '300';
        $order->tamanho42 = '500';
        $order->tamanho43 = '700';
        $order->tamanho44 = '20';
        $order->save();
    }
}
