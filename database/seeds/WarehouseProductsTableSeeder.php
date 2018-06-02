<?php

use App\WarehouseProduct;
use Illuminate\Database\Seeder;

class WarehouseProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warehouseProduct = new WarehouseProduct();
        $warehouseProduct->user_id = '1';
        $warehouseProduct->reference = 'e22pa78/1';
        $warehouseProduct->color = 'pt101e';
        $warehouseProduct->weight = '2000';
        $warehouseProduct->save();

        $warehouseProduct = new WarehouseProduct();
        $warehouseProduct->user_id = '1';
        $warehouseProduct->reference = 'e22pa78/1';
        $warehouseProduct->color = 'az3505f';
        $warehouseProduct->weight = '1000';
        $warehouseProduct->save();

        $warehouseProduct = new WarehouseProduct();
        $warehouseProduct->user_id = '1';
        $warehouseProduct->reference = 'nylom70/1';
        $warehouseProduct->color = 'pt101e';
        $warehouseProduct->weight = '2500';
        $warehouseProduct->save();


    }
}
