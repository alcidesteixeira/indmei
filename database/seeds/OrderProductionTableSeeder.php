<?php

use App\OrderProduction;
use Illuminate\Database\Seeder;

class OrderProductionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orderProduction = new OrderProduction();
        $orderProduction->order_id = '1';
        $orderProduction->user_id = '1';
        $orderProduction->tamanho = '1';
        $orderProduction->cor = '1';
        $orderProduction->value = '100';
        $orderProduction->save();

        $orderProduction = new OrderProduction();
        $orderProduction->order_id = '1';
        $orderProduction->user_id = '1';
        $orderProduction->tamanho = '1';
        $orderProduction->cor = '1';
        $orderProduction->value = '50';
        $orderProduction->save();

        $orderProduction = new OrderProduction();
        $orderProduction->order_id = '1';
        $orderProduction->user_id = '1';
        $orderProduction->tamanho = '1';
        $orderProduction->cor = '1';
        $orderProduction->value = '160';
        $orderProduction->save();
    }
}
