<?php

use App\SampleArticleStatus;
use Illuminate\Database\Seeder;

class SampleArticleStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = new SampleArticleStatus();
        $role_admin->status = 'Em produção de Amostra';
        $role_admin->save();

        $role_admin = new SampleArticleStatus();
        $role_admin->status = 'Não preparado a Produzir';
        $role_admin->save();

        $role_admin = new SampleArticleStatus();
        $role_admin->status = 'Pronto a produzir';
        $role_admin->save();

        $role_admin = new SampleArticleStatus();
        $role_admin->status = 'Em Produção';
        $role_admin->save();

        $role_admin = new SampleArticleStatus();
        $role_admin->status = 'Produzido';
        $role_admin->save();

        $role_admin = new SampleArticleStatus();
        $role_admin->status = 'Em Distribuição';
        $role_admin->save();
    }
}
