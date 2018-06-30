<?php

use App\Client;
use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client();
        $client ->client = 'cliente 1';
        $client ->email = 'zzz@gmail.com';
        $client ->nif = '123456789';
        $client ->description = 'cliente de braga';
        $client ->save();

        $client = new Client();
        $client ->client = 'cliente 2';
        $client ->email = 'zzz@gmail.com';
        $client ->nif = '123456789';
        $client ->description = 'cliente de faro';
        $client ->save();

        $client = new Client();
        $client ->client = 'cliente 3';
        $client ->email = 'zzz@gmail.com';
        $client ->nif = '123456789';
        $client ->description = 'cliente de guimarães';
        $client ->save();
    }
}
