<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name', 'Admin')->first();
        $role_article  = Role::where('name', 'Gestor de Amostra de Artigo')->first();
        $role_order  = Role::where('name', 'Gestor de Encomenda')->first();

        $admin = new User();
        $admin->name = 'Alcides Teixeira';
        $admin->email = 'alcides.mn.teixeira@gmail.com';
        $admin->password = bcrypt('tiptop');
        $admin->save();
        $admin->roles()->attach($role_admin);
        $admin->roles()->attach($role_article);

        $manager = new User();
        $manager->name = 'José Costa';
        $manager->email = 'cides26@gmail.com';
        $manager->password = bcrypt('tiptop');
        $manager->save();
        $manager->roles()->attach($role_article);

        $admin = new User();
        $admin->name = 'Gestor de Encomenda';
        $admin->email = 'alcides.mn.teixeira+10@gmail.com';
        $admin->password = bcrypt('tiptop');
        $admin->save();
        $admin->roles()->attach($role_order);
    }
}
