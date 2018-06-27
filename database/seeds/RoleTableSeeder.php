<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = new Role();
        $role_admin->name = 'Admin';
        $role_admin->description = 'O role admin tem acesso a todas as funcionalidades.';
        $role_admin->save();

        $role_guest = new Role();
        $role_guest->name = 'Convidado';
        $role_guest->description = 'O role convidado tem acessos restritos e apenas pode visualizar alguma info.';
        $role_guest->save();

        $role_article = new Role();
        $role_article->name = 'Gestor de Amostra de Artigo';
        $role_article->description = 'O role de gestor de amostra de artigo tem acesso a todas as funcionalidades relacionadas com criar amostras de artigo.';
        $role_article->save();

        $role_order = new Role();
        $role_order->name = 'Gestor de Encomenda';
        $role_order->description = 'O gestor de encomenda de artigo poderá realizar todas as operações relacionadas com amostras de artigos.';
        $role_order->save();

        $role_warehouse = new Role();
        $role_warehouse->name = 'Gestor de Armazém';
        $role_warehouse->description = 'O role de gestor de armazém tem acesso a todas as funcionalidades relacionadas com a gestão de Stock.';
        $role_warehouse->save();

        $role_worker = new Role();
        $role_worker->name = 'Operário';
        $role_worker->description = 'O role operário terá acesso às encomendas prontas a produzir, e a todas as suas características.';
        $role_worker->save();

        $role_worker = new Role();
        $role_worker->name = 'Gestor de Orçamentação';
        $role_worker->description = 'O gestor de orçamentação poderá aceder às amostras e ao seu valor, para depois gerar o seu orçamento.';
        $role_worker->save();
    }
}
