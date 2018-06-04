<?php

use App\WarehouseProductSpec;
use Illuminate\Database\Seeder;

class WarehouseProductSpecsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warehouseProduct = new WarehouseProductSpec();
        $warehouseProduct->warehouse_product_id = '1';
        $warehouseProduct->color = 'pt101e';
        $warehouseProduct->weight = '2000';
        $warehouseProduct->save();

        $warehouseProduct = new WarehouseProductSpec();
        $warehouseProduct->warehouse_product_id = '1';
        $warehouseProduct->color = 'az3505f';
        $warehouseProduct->weight = '1000';
        $warehouseProduct->save();

        $warehouseProduct = new WarehouseProductSpec();
        $warehouseProduct->warehouse_product_id = '1';
        $warehouseProduct->color = 'pt101e';
        $warehouseProduct->weight = '2500';
        $warehouseProduct->save();
        $warehouseProduct = new WarehouseProductSpec();
        $warehouseProduct->warehouse_product_id = '2';
        $warehouseProduct->color = 'pt101e';
        $warehouseProduct->weight = '2000';
        $warehouseProduct->save();

        $warehouseProduct = new WarehouseProductSpec();
        $warehouseProduct->warehouse_product_id = '2';
        $warehouseProduct->color = 'az3505f';
        $warehouseProduct->weight = '1000';
        $warehouseProduct->save();

        $warehouseProduct = new WarehouseProductSpec();
        $warehouseProduct->warehouse_product_id = '2';
        $warehouseProduct->color = 'pt101e';
        $warehouseProduct->weight = '2500';
        $warehouseProduct->save();
        $warehouseProduct = new WarehouseProductSpec();
        $warehouseProduct->warehouse_product_id = '3';
        $warehouseProduct->color = 'pt101e';
        $warehouseProduct->weight = '2000';
        $warehouseProduct->save();

        $warehouseProduct = new WarehouseProductSpec();
        $warehouseProduct->warehouse_product_id = '3';
        $warehouseProduct->color = 'az3505f';
        $warehouseProduct->weight = '1000';
        $warehouseProduct->save();

        $warehouseProduct = new WarehouseProductSpec();
        $warehouseProduct->warehouse_product_id = '3';
        $warehouseProduct->color = 'pt101e';
        $warehouseProduct->weight = '2500';
        $warehouseProduct->save();
    }
}
