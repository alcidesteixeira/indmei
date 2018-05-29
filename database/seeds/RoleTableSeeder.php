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

        $role_guest = new Role();
        $role_guest->name = 'Gestor de Artigo';
        $role_guest->description = 'O gestor de artigo poderá fazer todas as operações relacionadas com artigos.';
        $role_guest->save();

        $role_guest = new Role();
        $role_guest->name = 'Gestor de Encomenda';
        $role_guest->description = 'O role convidado tem acessos restritos e apenas pode visualizar alguma info.';
        $role_guest->save();

        $role_guest = new Role();
        $role_guest->name = 'Gestor de Armazém';
        $role_guest->description = 'O role convidado tem acessos restritos e apenas pode visualizar alguma info.';
        $role_guest->save();

        $role_guest = new Role();
        $role_guest->name = 'Operário';
        $role_guest->description = 'O role convidado tem acessos restritos e apenas pode visualizar alguma info.';
        $role_guest->save();
    }
}
