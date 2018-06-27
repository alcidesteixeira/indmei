<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WarehouseProductsHistoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('warehouse_products_history')->insert(
            [
                'warehouse_product_spec_id' => '9',
                'user_id' => 1,
                'inout' => 'in',
                'cost' => '100',
                'weight' => '300',
                'description' => 'fornecedor x',
                'receipt' => 'fatura.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

    }
}
