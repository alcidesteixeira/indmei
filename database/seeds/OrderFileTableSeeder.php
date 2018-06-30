<?php

use App\OrderFile;
use Illuminate\Database\Seeder;

class OrderFileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = new OrderFile();
        $file->order_id = '1';
        $file->url = 'test.jpg';
        $file->save();

        $file = new OrderFile();
        $file->order_id = '1';
        $file->url = 'test2.jpg';
        $file->save();

        $file = new OrderFile();
        $file->order_id = '1';
        $file->url = 'test3.jpg';
        $file->save();
    }
}
