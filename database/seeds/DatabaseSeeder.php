<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Role comes before User seeder here.
        $this->call(RoleTableSeeder::class);
        // User seeder will use the roles above created.
        $this->call(UserTableSeeder::class);

        //Seed Sample Articles
        $this->call(SampleArticleColorTableSeeder::class);
        $this->call(SampleArticleStatusTableSeeder::class);
        $this->call(SampleArticleStepTableSeeder::class);
        $this->call(SampleArticleTableSeeder::class);
        $this->call(SampleArticleWireTableSeeder::class);
        $this->call(SampleArticleGuiafioTableSeeder::class);

        //Seed Warehouse
        $this->call(WarehouseProductsTableSeeder::class);
        $this->call(WarehouseProductSpecsTableSeeder::class);
        $this->call(WarehouseProductsHistoryTableSeeder::class);

        //Seed Suppliers
        $this->call(SupplierTableSeeder::class);

        //Seed Clients
        $this->call(ClientTableSeeder::class);

        //Seed Orders
        $this->call(OrderTableSeeder::class);
        $this->call(OrderFileTableSeeder::class);

    }
}
