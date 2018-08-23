<?php

use App\Quotation;
use Illuminate\Database\Seeder;

class QuotationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quotation = new Quotation();
        $quotation->order_id = '2';
        $quotation->order_sample_cost_1 = '5.66';
        $quotation->order_sample_cost_2 = '1';
        $quotation->order_sample_cost_3 = '2';
        $quotation->order_sample_cost_4 = '5.66';
        $quotation->tags = '1.6';
        $quotation->boxes = '15';
        $quotation->defect = '8';
        $quotation->manpower = '20';
        $quotation->manpower = '20';
        $quotation->other_costs = '20';
        $quotation->value_sent = '200';
        $quotation->save();
    }
}
