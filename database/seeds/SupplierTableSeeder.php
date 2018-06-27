<?php

use App\Supplier;
use Illuminate\Database\Seeder;

class SupplierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplier = new Supplier();
        $supplier ->supplier = 'fornecedor 1';
        $supplier ->nif = '123456789';
        $supplier ->description = 'fornecedor de braga';
        $supplier ->save();

        $supplier = new Supplier();
        $supplier ->supplier = 'fornecedor 2';
        $supplier ->nif = '123456789';
        $supplier ->description = 'fornecedor de viana do castelo';
        $supplier ->save();

        $supplier = new Supplier();
        $supplier ->supplier = 'fornecedor 3';
        $supplier ->nif = '123456789';
        $supplier ->description = 'fornecedor do porto';
        $supplier ->save();
    }
}
