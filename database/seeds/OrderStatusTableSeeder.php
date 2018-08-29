<?php

use App\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = new OrderStatus();
        $status->status = 'Encomenda Recebida';
        $status->save();
        $status = new OrderStatus();
        $status->status = 'A produzir amostra';
        $status->save();
        $status = new OrderStatus();
        $status->status = 'A criar orçamento';
        $status->save();
        $status = new OrderStatus();
        $status->status = 'A aguardar resposta do cliente';
        $status->save();
        $status = new OrderStatus();
        $status->status = 'Em produção';
        $status->save();
        $status = new OrderStatus();
        $status->status = 'À espera de matéria-prima';
        $status->save();
        $status = new OrderStatus();
        $status->status = 'Produzido';
        $status->save();
        $status = new OrderStatus();
        $status->status = 'Em distribuição';
        $status->save();
    }
}
